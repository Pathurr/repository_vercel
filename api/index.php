<?php

$cachePath = '/tmp/laravel';
$viewPath = $cachePath . '/views';

foreach ([$cachePath, $viewPath] as $path) {
    if (! is_dir($path)) {
        @mkdir($path, 0777, true);
    }
}

$serverlessEnv = [
    'APP_CONFIG_CACHE' => $cachePath . '/config.php',
    'APP_EVENTS_CACHE' => $cachePath . '/events.php',
    'APP_PACKAGES_CACHE' => $cachePath . '/packages.php',
    'APP_ROUTES_CACHE' => $cachePath . '/routes.php',
    'APP_SERVICES_CACHE' => $cachePath . '/services.php',
    'VIEW_COMPILED_PATH' => $viewPath,
    'CACHE_STORE' => 'array',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
];

foreach ($serverlessEnv as $key => $value) {
    if (getenv($key) === false) {
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

require __DIR__ . '/../public/index.php';
