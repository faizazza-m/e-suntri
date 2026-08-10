<?php

// Vercel serverless: /tmp is the only writable directory at runtime
// Redirect Laravel's storage to /tmp to allow view compilation, logging, etc.
$tmpStorage = '/tmp/storage';

$dirs = [
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/framework/testing',
    $tmpStorage . '/logs',
    $tmpStorage . '/app/public',
    $tmpStorage . '/app/private',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

$_ENV['APP_STORAGE'] = $tmpStorage;

$_M = $_SERVER;

$_M['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_M['SCRIPT_NAME'] = '/index.php';
$_M['PHP_SELF'] = '/index.php';

require __DIR__ . '/../public/index.php';