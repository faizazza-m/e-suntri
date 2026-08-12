<?php
// ULTRA MINIMAL TEST - tidak ada Laravel sama sekali
echo "PHP Works! Version: " . PHP_VERSION . "\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Memory: " . round(memory_get_usage()/1024/1024, 2) . " MB\n";
echo "/tmp writable: " . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";
echo "vendor exists: " . (is_dir(__DIR__ . '/../vendor') ? 'YES' : 'NO') . "\n";
echo "public/index.php exists: " . (file_exists(__DIR__ . '/../public/index.php') ? 'YES' : 'NO') . "\n";
echo ".env exists: " . (file_exists(__DIR__ . '/../.env') ? 'YES' : 'NO') . "\n";
echo "\nDone.\n";