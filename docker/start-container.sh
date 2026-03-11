#!/usr/bin/env bash
set -euo pipefail

PORT_VALUE="${PORT:-8080}"
NGINX_TEMPLATE="/etc/nginx/sites-available/default"

sed -i "s/__PORT__/${PORT_VALUE}/g" "${NGINX_TEMPLATE}"

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
