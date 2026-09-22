<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Vercel Read-Only Storage Fix
|--------------------------------------------------------------------------
| Mengalihkan folder storage & bootstrap cache ke /tmp agar writable di Vercel
*/
$appfile = __DIR__ . '/../bootstrap/app.php';

// Siapkan folder temporary di /tmp Vercel
$storageFolders = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageFolders as $folder) {
    if (!is_dir($folder)) {
        @mkdir($folder, 0777, true);
    }
}

// Set environment variable untuk jalur storage baru
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once $appfile;

// Bind path storage ke /tmp
$app->useStoragePath('/tmp/storage');

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
