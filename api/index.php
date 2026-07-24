<?php
/**
 * Forward Vercel requests to normal index.php
 */
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED);

$appStorage = '/tmp/storage';
if (!is_dir($appStorage)) {
    mkdir($appStorage, 0777, true);
    mkdir($appStorage . '/framework/views', 0777, true);
    mkdir($appStorage . '/framework/cache/data', 0777, true);
    mkdir($appStorage . '/framework/sessions', 0777, true);
    mkdir($appStorage . '/logs', 0777, true);
}

$_ENV['APP_STORAGE'] = $appStorage;
putenv("APP_STORAGE={$appStorage}");

require __DIR__ . '/../public/index.php';
