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
