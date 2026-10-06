#!/bin/sh
set -eu

listen_port="${PORT:-8080}"
case "$listen_port" in
    ''|*[!0-9]*) echo 'PORT must be an integer.' >&2; exit 1 ;;
esac
if [ "$listen_port" -lt 1 ] || [ "$listen_port" -gt 65535 ]; then
    echo 'PORT must be between 1 and 65535.' >&2
    exit 1
fi

# Apply at runtime too, since the deployed Apache configuration can differ.
a2dismod mpm_event mpm_worker
a2enmod mpm_prefork
printf 'Listen %s\n' "$listen_port" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$listen_port>/" /etc/apache2/sites-available/000-default.conf

# Railway mounts upload volumes at runtime; make the mounted directory writable.
mkdir -p /var/www/html/public/uploads /var/www/html/storage/logs
chown www-data:www-data /var/www/html/public/uploads /var/www/html/storage/logs
apache2ctl -t
exec apache2-foreground
