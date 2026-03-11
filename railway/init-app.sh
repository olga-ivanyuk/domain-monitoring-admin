#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if [ "${RUN_NPM_BUILD:-false}" = "true" ]; then
  if command -v npm >/dev/null 2>&1; then
    echo "Installing Node dependencies and building assets..."
    npm ci
    npm run build
  else
    echo "Warning: RUN_NPM_BUILD=true, but npm is not available in this image. Skipping frontend build."
  fi
fi

echo "Preparing Laravel runtime..."
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

if [ -z "${APP_KEY:-}" ]; then
  if [ -f ".env" ]; then
    echo "APP_KEY is not set, generating a new key in .env..."
    php artisan key:generate --force --no-interaction
  else
    echo "Error: APP_KEY is not set and .env file is missing."
    echo "Set APP_KEY in Railway Variables (format: base64:...) and redeploy."
    exit 1
  fi
fi

php artisan migrate --force
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Init completed."
