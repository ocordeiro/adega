#!/bin/sh
set -e

php artisan db:create
php artisan migrate --force

exec "$@"
