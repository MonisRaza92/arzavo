<?php
// Step-by-step execution with error handling
file_put_contents('debug.log', "=== Starting Deployment ===\n", FILE_APPEND);

try {
    // 1. Change directory and git pull
    $output1 = shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && pwd 2>&1');
    file_put_contents('debug.log', "Current directory: " . $output1 . "\n", FILE_APPEND);
    
    // 2. Git reset
    $output2 = shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && git reset --hard 2>&1');
    file_put_contents('debug.log', "Git reset: " . $output2 . "\n", FILE_APPEND);
    
    // 3. Git pull
    $output3 = shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && git pull 2>&1');
    file_put_contents('debug.log', "Git pull: " . $output3 . "\n", FILE_APPEND);
    
    // 4. Laravel commands
    $output4 = shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && php artisan migrate --force 2>&1');
    file_put_contents('debug.log', "Migrations: " . $output4 . "\n", FILE_APPEND);
    
    $output5 = shell_exec('cd ~/domains/prakashjhaclasses.com/public_html && php artisan optimize 2>&1');
    file_put_contents('debug.log', "Optimize: " . $output5 . "\n", FILE_APPEND);
    
    file_put_contents('debug.log', "=== Deployment Completed ===\n\n", FILE_APPEND);
    echo "Deployment successful! Check debug.log for details.";
    
} catch (Exception $e) {
    file_put_contents('debug.log', "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    echo "Error occurred: " . $e->getMessage();
}
?>