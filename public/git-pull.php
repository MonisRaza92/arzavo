<?php
// Simple test version
file_put_contents('debug.log', "Webhook called at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Basic response
echo "Webhook is working!";
?>