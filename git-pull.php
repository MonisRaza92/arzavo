<?php
// GitHub webhook secret - same as GitHub mein jo secret daala hai
$secret = "arzavo@78692";

// GitHub se mila data verify karo
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

if ($signature) {
    $hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);

    if (hash_equals($hash, $signature)) {
        // Log file start
        $log = "=== Deployment Started: " . date('Y-m-d H:i:s') . " ===\n";

        // 1. Git reset hard karo
        $log .= "1. Git reset --hard:\n";
        $log .= shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && git reset --hard 2>&1') . "\n";

        // 2. Git pull karo
        $log .= "2. Git pull:\n";
        $log .= shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && git pull 2>&1') . "\n";

        // 3. Laravel migrations run karo
        $log .= "3. Running migrations:\n";
        $log .= shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && php artisan migrate --force 2>&1') . "\n";

        // 4. Laravel optimize karo
        $log .= "4. Optimizing:\n";
        $log .= shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && php artisan optimize 2>&1') . "\n";

        $log .= "=== Deployment Completed ===\n\n";

        // Log file save karo
        file_put_contents('git-pull.log', $log, FILE_APPEND);

        echo "Deployment completed successfully! Check git-pull.log for details.";
    } else {
        http_response_code(403);
        echo "Invalid signature";
    }
} else {
    echo "Webhook setup working! Ready for GitHub pushes.";
}
