<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;

class DomainController
{
    public function verifyDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string'
        ]);

        $domain = strtolower(trim($request->domain));
        $domain = str_replace(['https://', 'http://'], '', $domain);
        $domain = explode('/', $domain)[0];

        // --------------------------
        // 1. Find tenant
        // --------------------------
        $tenant = Tenant::where('custom_domain', $domain)->first();
        if (! $tenant) {
            return response()->json([
                'status' => 'error',
                'message' => "No tenant found for domain: $domain"
            ], 404);
        }

        // --------------------------
        // 2. Detect server IP
        // --------------------------
        $serverIp = trim(shell_exec("curl -s http://checkip.amazonaws.com"));

        $dns = @dns_get_record($domain, DNS_A);
        if (empty($dns)) {
            return response()->json([
                'status' => 'error',
                'message' => "No A-record found for $domain. Add A → $serverIp"
            ]);
        }

        $points = collect($dns)->contains(fn($r) => ($r['ip'] ?? null) === $serverIp);

        if (! $points) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain not pointing. Add:<br>A → $serverIp"
            ]);
        }

        // --------------------------
        // 3. CREATE NGINX FILE
        // --------------------------
        $nginxConf = "
server {
    listen 80;
    listen [::]:80;

    server_name $domain;

    root /var/www/arzavo/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
        ";

        file_put_contents("/etc/nginx/sites-available/$domain.conf", $nginxConf);

        // LINK ENABLE SITE
        shell_exec("sudo ln -sf /etc/nginx/sites-available/$domain.conf /etc/nginx/sites-enabled/$domain.conf");

        // Reload nginx
        shell_exec("sudo systemctl reload nginx");

        // --------------------------
        // 4. INSTALL SSL
        // --------------------------
        $cmd = "sudo certbot --nginx -d $domain --non-interactive --agree-tos -m monisrazakhan2001@gmail.com --redirect 2>&1";
        $output = shell_exec($cmd);

        if (! str_contains($output, 'Congratulations')) {
            return response()->json([
                'status' => 'error',
                'message' => "SSL FAILED",
                'certbot_output' => $output
            ], 500);
        }

        // --------------------------
        // 5. Update tenant
        // --------------------------
        $tenant->update([
            'domain_verified' => true,
            'domain_verified_at' => now(),
            'domain_ssl_output' => $output
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "$domain is LIVE with SSL",
            'output' => $output
        ]);
    }
}
