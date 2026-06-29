#!/usr/bin/env bash

echo "[STARTUP] Script started" >&2

# Map Railway PostgreSQL env vars to Laravel DB_* vars
[ -n "$PGHOST" ] && export DB_HOST="$PGHOST"
[ -n "$PGPORT" ] && export DB_PORT="$PGPORT"
[ -n "$PGUSER" ] && export DB_USERNAME="$PGUSER"
[ -n "$PGPASSWORD" ] && export DB_PASSWORD="$PGPASSWORD"
[ -n "$PGDATABASE" ] && export DB_DATABASE="$PGDATABASE"
export DB_CONNECTION="pgsql"

# Sanitize env vars
while IFS= read -r -d '' entry; do
  k="${entry%%=*}"
  v="${entry#*=}"
  clean=$(printf "%s" "$v" | tr -d '\r\n\t')
  if [ "$v" != "$clean" ] 2>/dev/null; then
    echo "[STARTUP] DIRTY: $k" >&2
  fi
  export "$k"="$clean" 2>/dev/null || true
done < /proc/self/environ 2>/dev/null || true

export APP_URL="http://localhost:${PORT:-8080}"
export APP_ENV=local
export APP_DEBUG=true

echo "[STARTUP] DB_HOST=$DB_HOST DB_PORT=$DB_PORT DB_DATABASE=$DB_DATABASE" >&2
echo "[STARTUP] APP_URL=$APP_URL" >&2

echo "[STARTUP] Clearing cached config (may have stale APP_URL)..." >&2
rm -f bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/events.php 2>/dev/null || true
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true

echo "[STARTUP] Generating fresh APP_KEY..." >&2
# Remove Railway's APP_KEY (might be invalid) and generate a new one
unset APP_KEY 2>/dev/null || true
php artisan key:generate --force 2>&1 || true
# Re-read the generated key from .env into environment
export APP_KEY=$(grep '^APP_KEY=' .env | cut -d= -f2-) 2>/dev/null || true
echo "[STARTUP] APP_KEY set" >&2

echo "[STARTUP] Running migrations..." >&2
php artisan migrate --force 2>&1 || echo "[STARTUP] Migration skipped" >&2

echo "[STARTUP] Starting PHP server..." >&2
exec php -S 0.0.0.0:${PORT:-8080} -t public server.php
