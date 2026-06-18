#!/usr/bin/env sh
set -e

cd /var/www/html

mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

php artisan package:discover --ansi || true

exec "$@"
