#!/usr/bin/env bash
set -e

# Sanitize env vars that may contain CR/LF/TAB from Railway Docker injection
sanitize() {
  for var in "$@"; do
    val=$(printf '%s' "${!var}" | tr -d '\r\n\t' || true)
    export "$var"="$val"
  done
}

sanitize APP_URL APP_KEY DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD

# Override APP_URL with a clean value based on Railway PORT
export APP_URL="http://0.0.0.0:${PORT:-8080}"

echo "[STARTUP] Starting container..." >&2
echo "[STARTUP] PHP version: $(php -v 2>&1 | head -1)" >&2
echo "[STARTUP] PORT: ${PORT:-not set}" >&2
echo "[STARTUP] APP_URL: ${APP_URL}" >&2
echo "[STARTUP] ENV dump:" >&2
env | sort >&2 || true

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
