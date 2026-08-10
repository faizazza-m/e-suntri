<?php

// Vercel serverless: only /tmp is writable at runtime.
// Create required Laravel storage directories in /tmp.
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
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// Tell Laravel to use /tmp/storage as the storage path
$_ENV['APP_STORAGE']    = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;
putenv('APP_STORAGE=' . $storagePath);

require __DIR__ . '/../public/index.php';