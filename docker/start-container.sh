#!/usr/bin/env bash
set -euo pipefail

PORT_VALUE="${PORT:-8080}"
NGINX_AVAILABLE="/etc/nginx/sites-available/default"
NGINX_ENABLED="/etc/nginx/sites-enabled/default"

for NGINX_FILE in "${NGINX_AVAILABLE}" "${NGINX_ENABLED}"; do
  if [ -f "${NGINX_FILE}" ]; then
    sed -i "s/__PORT__/${PORT_VALUE}/g" "${NGINX_FILE}"
  fi
done

if grep -R "__PORT__" /etc/nginx/sites-available /etc/nginx/sites-enabled >/dev/null 2>&1; then
  echo "Error: nginx port placeholder was not replaced."
  exit 1
fi

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
