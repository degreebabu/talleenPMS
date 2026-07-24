<?php
/**
 * Forward Vercel requests to normal index.php
 */
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED);

$appStorage = '/tmp/storage';
$appBootstrap = '/tmp/bootstrap';
if (!is_dir($appStorage)) {
    mkdir($appStorage, 0777, true);
    mkdir($appStorage . '/framework/views', 0777, true);
    mkdir($appStorage . '/framework/cache/data', 0777, true);
    mkdir($appStorage . '/framework/sessions', 0777, true);
    mkdir($appStorage . '/logs', 0777, true);
    
    mkdir($appBootstrap, 0777, true);
    mkdir($appBootstrap . '/cache', 0777, true);
}

$_ENV['APP_STORAGE'] = $appStorage;
putenv("APP_STORAGE={$appStorage}");
$_ENV['APP_BOOTSTRAP_PATH'] = $appBootstrap;
putenv("APP_BOOTSTRAP_PATH={$appBootstrap}");

// Force debug mode and stderr logging for Vercel troubleshooting
$_ENV['APP_DEBUG'] = 'true';
putenv('APP_DEBUG=true');
$_ENV['LOG_CHANNEL'] = 'stderr';
putenv('LOG_CHANNEL=stderr');

require __DIR__ . '/../public/index.php';
