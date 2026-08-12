<?php

// TEMPORARY DEBUG - HAPUS SETELAH SELESAI
// Force show ALL errors before anything else
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Override environment to enable debug
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';
putenv('APP_DEBUG=true');

// Set storage to /tmp
$storagePath = '/tmp/storage';
foreach ([
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/logs',
    $storagePath . '/app/public',
    $storagePath . '/app/private',
] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;
putenv('APP_STORAGE=' . $storagePath);

echo "<!-- debug-start -->\n";
flush();

// Register a shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        echo "\n<!-- FATAL ERROR -->\n";
        echo "<div style='background:red;color:white;padding:20px;font-family:monospace;z-index:9999;position:fixed;top:0;left:0;right:0;'>";
        echo "<strong>FATAL PHP ERROR:</strong><br>";
        echo htmlspecialchars($error['message']) . "<br>";
        echo "File: " . htmlspecialchars($error['file']) . "<br>";
        echo "Line: " . $error['line'];
        echo "</div>";
    }
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "\n<!-- PHP ERROR: [$errno] $errstr in $errfile:$errline -->\n";
});

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<div style='background:red;color:white;padding:20px;font-family:monospace;'>";
    echo "<strong>EXCEPTION: " . get_class($e) . "</strong><br>";
    echo htmlspecialchars($e->getMessage()) . "<br><br>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}