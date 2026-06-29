<?php

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
