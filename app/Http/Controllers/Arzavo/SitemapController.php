<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Support\Carbon;

class SitemapController
{
    public function index()
    {
        $urls = [];
        $base = rtrim(config('app.url', 'https://arzavo.com'), '/');

        $add = function (string $path, string $priority = '0.7', string $freq = 'weekly', $lastmod = null) use (&$urls, $base) {
            $loc = $path === '/' ? $base : $base . '/' . ltrim($path, '/');
            $urls[$loc] = [
                'loc' => $loc,
                'priority' => $priority,
                'changefreq' => $freq,
                'lastmod' => $lastmod
                    ? Carbon::parse($lastmod)->toAtomString()
                    : now()->toAtomString(),
            ];
        };

        // 1. Homepage
        $add('/', '1.0', 'daily');

        // 2. Primary Marketing Pages
        $add('/features', '0.9', 'weekly');
        $add('/pricing', '0.9', 'weekly');
        $add('/about', '0.9', 'weekly');
        $add('/contact', '0.9', 'weekly');

        // 3. Documentation Index & Pages
        $add('/documentation', '0.8', 'weekly');

        $docsPath = resource_path('views/arzavo/website/documentation/pages');
        if (file_exists($docsPath)) {
            $files = glob($docsPath . '/*.blade.php');
            foreach ($files as $file) {
                $filename = basename($file, '.blade.php');
                $mtime = filemtime($file);
                $add('/documentation/' . $filename, '0.7', 'weekly', $mtime);
            }
        }

        // 4. Trust & Legal Pages
        $legalPages = [
            '/privacy',
            '/terms',
            '/refund-policy',
            '/cookie-policy',
            '/data-retention',
            '/acceptable-use',
            '/security-policy',
            '/data-ownership',
            '/student-privacy',
            '/communication-policy',
            '/dpa',
            '/subprocessors',
            '/trust',
            '/legal-notices',
        ];

        foreach ($legalPages as $page) {
            $add($page, '0.5', 'monthly');
        }

        return response()
            ->view('arzavo.website.sitemap', [
                'urls' => array_values($urls)
            ])
            ->header('Content-Type', 'text/xml');
    }
}
