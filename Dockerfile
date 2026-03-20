FROM dunglas/frankenphp:1.11.1-php8.4-alpine

RUN apk add --no-cache \
    curl \
    unzip \
    nodejs \
    npm \
    imagemagick \
    imagemagick-dev

RUN install-php-extensions bcmath pcntl mysqli pdo_mysql intl zip redis ffi exif imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/conf.d/99-app.ini

COPY . /var/www/app
WORKDIR /var/www/app

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN php artisan filament:assets && \
    php artisan view:cache

EXPOSE 8000

CMD ["php", "artisan", "octane:frankenphp", "--workers=1", "--max-requests=500", "--caddyfile=Caddyfile"]
