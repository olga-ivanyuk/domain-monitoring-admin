#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

if [ "${RUN_MIGRATIONS_ON_START:-false}" = "true" ]; then
  echo "Running migrations before worker start..."
  php artisan migrate --force --no-interaction
fi

echo "Starting Laravel queue worker..."
exec php artisan queue:work --sleep=3 --tries=3 --timeout=120 --max-time=3600 --no-interaction
