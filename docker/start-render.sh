#!/bin/sh
set -eu

render_port="${PORT:-10000}"
sed -ri "s/Listen 80/Listen ${render_port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:10000>/<VirtualHost *:${render_port}>/" /etc/apache2/sites-available/000-default.conf

php artisan migrate --force
php artisan config:cache
php artisan view:cache

exec apache2-foreground
