#!/usr/bin/env bash
set -e

echo "[STARTUP] Starting container..." >&2
echo "[STARTUP] PHP version: $(php -v 2>&1 | head -1)" >&2
echo "[STARTUP] Working directory: $(pwd)" >&2
echo "[STARTUP] PORT: ${PORT:-not set}" >&2
echo "[STARTUP] APP_ENV: ${APP_ENV:-not set}" >&2
echo "[STARTUP] DB_CONNECTION: ${DB_CONNECTION:-not set}" >&2

if [ -f .env ]; then
    echo "[STARTUP] .env file exists" >&2
else
    echo "[STARTUP] .env file MISSING - copying from .env.example" >&2
    cp .env.example .env 2>/dev/null || true
fi

echo "[STARTUP] Running storage:link..." >&2
php artisan storage:link --force 2>&1 || echo "[STARTUP] storage:link failed (non-fatal)" >&2

echo "[STARTUP] Running migrate --force..." >&2
php artisan migrate --force 2>&1 || echo "[STARTUP] migrate failed (non-fatal)" >&2

echo "[STARTUP] Starting PHP built-in server on 0.0.0.0:${PORT:-8080}..." >&2
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
