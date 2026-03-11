#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

if [ "${RUN_MIGRATIONS_ON_START:-true}" = "true" ]; then
  echo "Running migrations before app start..."
  php artisan migrate --force --no-interaction
fi

echo "Starting Laravel app..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
