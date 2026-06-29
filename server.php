<?php

// Map Railway PostgreSQL env vars to Laravel DB_* vars (fallback)
foreach (['HOST', 'PORT', 'USER', 'PASSWORD', 'DATABASE'] as $key) {
    $pgKey = 'PG'.$key;
    $dbKey = 'DB_'.$key;
    if (empty($_SERVER[$dbKey]) && !empty($_SERVER[$pgKey])) {
        $_SERVER[$dbKey] = $_SERVER[$pgKey];
        putenv("$dbKey={$_SERVER[$pgKey]}");
    }
}
if (empty($_SERVER['DB_CONNECTION'])) {
    $_SERVER['DB_CONNECTION'] = 'pgsql';
    putenv('DB_CONNECTION=pgsql');
}

// Force APP_KEY from .env (Railway's injected key may be invalid)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with($line, 'APP_KEY=')) {
            $key = substr($line, 8);
            $_SERVER['APP_KEY'] = $key;
            $_ENV['APP_KEY'] = $key;
            putenv("APP_KEY=$key");
            break;
        }
    }
}

// Detect actual Railway URL and set as APP_URL for proper asset generation
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
$scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'http';
$appUrl = "$scheme://$host";
$_SERVER['APP_URL'] = $appUrl;
$_ENV['APP_URL'] = $appUrl;
putenv("APP_URL=$appUrl");

// Sanitize all $_SERVER values to prevent Symfony Invalid URI errors
foreach ($_SERVER as $key => $value) {
    if (is_string($value)) {
        $_SERVER[$key] = str_replace(["\r", "\n", "\t"], '', $value);
    }
}

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require __DIR__.'/public/index.php';
