#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

if [ "${RUN_MIGRATIONS_ON_START:-false}" = "true" ]; then
  echo "Running migrations before worker start..."
  php artisan migrate --force --no-interaction
fi

WORKER_CONNECTION="${WORKER_CONNECTION:-${QUEUE_CONNECTION:-database}}"
WORKER_QUEUE="${WORKER_QUEUE:-default}"

echo "Starting Laravel queue worker..."
echo "Worker config: connection=${WORKER_CONNECTION}, queue=${WORKER_QUEUE}, app_env=${APP_ENV:-unknown}"

exec php artisan queue:work "${WORKER_CONNECTION}" \
  --queue="${WORKER_QUEUE}" \
  --sleep=3 \
  --tries=3 \
  --timeout=120 \
  --max-time=3600 \
  --verbose \
  --no-interaction
