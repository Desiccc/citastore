#!/usr/bin/env bash

echo "[STARTUP] Script started" >&2

# Sanitize env vars via /proc/self/environ (null-delimited)
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

# Detect server type
if command -v apache2-foreground &>/dev/null; then
  echo "[STARTUP] Starting Apache..." >&2
  exec apache2-foreground
else
  echo "[STARTUP] Starting PHP built-in server..." >&2
  exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
fi
