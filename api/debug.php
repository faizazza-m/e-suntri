<?php

// TEMPORARY DEBUG FILE - HAPUS SETELAH SELESAI DEBUG
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>PHP Serverless Debug</h2>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>OS:</strong> " . PHP_OS . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</p>";
echo "<p><strong>SCRIPT_FILENAME:</strong> " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "</p>";
echo "<p><strong>/tmp writable:</strong> " . (is_writable('/tmp') ? 'YES' : 'NO') . "</p>";
echo "<p><strong>Base dir:</strong> " . __DIR__ . "</p>";
echo "<p><strong>public/index.php exists:</strong> " . (file_exists(__DIR__ . '/../public/index.php') ? 'YES' : 'NO') . "</p>";
echo "<p><strong>vendor/autoload.php exists:</strong> " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'YES' : 'NO') . "</p>";
echo "<p><strong>.env exists:</strong> " . (file_exists(__DIR__ . '/../.env') ? 'YES' : 'NO') . "</p>";

echo "<h3>Extensions:</h3><pre>";
echo implode(', ', get_loaded_extensions());
echo "</pre>";

echo "<h3>ENV Vars (safe ones):</h3><pre>";
$safe = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'SESSION_DRIVER', 'CACHE_STORE'];
foreach ($safe as $key) {
    echo "$key = " . (getenv($key) ?: '(not set)') . "\n";
}
echo "</pre>";

// Now try to boot Laravel and catch any error
echo "<h3>Laravel Boot Test:</h3>";
try {
    $storagePath = '/tmp/storage';
    foreach ([
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
        $storagePath . '/app/public',
    ] as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0775, true);
    }
    
    $_ENV['APP_STORAGE'] = $storagePath;
    putenv('APP_STORAGE=' . $storagePath);
    
    ob_start();
    require __DIR__ . '/../public/index.php';
    $output = ob_get_clean();
    echo "<p style='color:green'>✅ Laravel booted OK! Response length: " . strlen($output) . " bytes</p>";
    echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
} catch (\Throwable $e) {
    echo "<p style='color:red'>❌ ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
