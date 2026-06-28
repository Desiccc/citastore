#!/usr/bin/env bash

echo "[STARTUP] Script started" >&2

# Remove dangerous chars from all env vars
echo "[STARTUP] Sanitizing env vars..." >&2
while IFS= read -r -d '' entry; do
  k="${entry%%=*}"
  v="${entry#*=}"
  clean=$(printf "%s" "$v" | tr -d '\r\n\t')
  if [ "$v" != "$clean" ] 2>/dev/null; then
    echo "[STARTUP] DIRTY: $k" >&2
  fi
  export "$k"="$clean" 2>/dev/null || true
done < /proc/self/environ 2>/dev/null || true

export APP_URL="http://0.0.0.0:${PORT:-80}"
echo "[STARTUP] APP_URL=$APP_URL" >&2

echo "[STARTUP] Running migrations..." >&2
php artisan migrate --force 2>&1 || echo "[STARTUP] Migration skipped" >&2

echo "[STARTUP] Starting Apache..." >&2
exec apache2-foreground
