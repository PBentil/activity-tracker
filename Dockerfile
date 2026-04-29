FROM php:8.4-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear && php artisan cache:clear || true

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
