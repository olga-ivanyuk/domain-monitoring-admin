#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${APP_DIR}"

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if [ "${RUN_NPM_BUILD:-false}" = "true" ]; then
  echo "Installing Node dependencies and building assets..."
  npm ci
  npm run build
fi

echo "Preparing Laravel runtime..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY is not set, generating a new key..."
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Init completed."


#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x railway/init-app.sh`

# Exit the script if any command fails
set -e

# Run migrations
php artisan migrate --force

# Clear cache
php artisan optimize:clear

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
