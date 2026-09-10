FROM dunglas/frankenphp:php8.4-bookworm

WORKDIR /app

RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

ENV SERVER_NAME=:8080

EXPOSE 8080
