#!/usr/bin/env bash
set -e

echo "[STARTUP] Sanitizing env vars..." >&2

# Read env vars from /proc/self/environ (null-delimited, handles multiline)
while IFS= read -r -d '' entry; do
  k="${entry%%=*}"
  v="${entry#*=}"
  clean=$(printf "%s" "$v" | tr -d '\r\n\t' || true)
  if [ "$v" != "$clean" ] 2>/dev/null; then
    echo "[STARTUP] DIRTY: $k contains CR/LF/TAB" >&2
  fi
  export "$k"="$clean"
done < /proc/self/environ

export APP_URL="http://0.0.0.0:${PORT:-80}"
echo "[STARTUP] APP_URL=$APP_URL" >&2

echo "[STARTUP] Running migrations..." >&2
php artisan migrate --force 2>&1 || echo "[STARTUP] Migration skipped" >&2

echo "[STARTUP] Starting Apache..." >&2
exec apache2-foreground
