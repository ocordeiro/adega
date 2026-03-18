#!/bin/sh
set -e

# Gera APP_KEY se não estiver definida
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY não definida — gerando automaticamente..."
    php artisan key:generate --force
fi

# Cacheia config agora que as variáveis de ambiente estão disponíveis
php artisan config:cache

php artisan db:create
php artisan migrate --force

exec "$@"
