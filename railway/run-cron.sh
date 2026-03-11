#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

echo "Starting Laravel scheduler loop..."
while true; do
  php artisan schedule:run --verbose --no-interaction
  sleep 60
done
