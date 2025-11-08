<?php
// Test 1: Check if shell_exec is allowed
file_put_contents('debug.log', "=== Test 1 - Shell Exec Check ===\n", FILE_APPEND);

// Check if shell_exec function is available
if (!function_exists('shell_exec')) {
    file_put_contents('debug.log', "ERROR: shell_exec function is disabled\n", FILE_APPEND);
    echo "shell_exec is disabled";
    exit;
}

// Test basic command
$test_cmd = shell_exec('whoami 2>&1');
file_put_contents('debug.log', "Current user: " . $test_cmd . "\n", FILE_APPEND);

// hvb

echo "Basic shell_exec working. User: " . $test_cmd;
