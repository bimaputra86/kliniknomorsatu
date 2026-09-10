FROM dunglas/frankenphp:php8.4-bookworm

WORKDIR /app

RUN apt-get update && apt-get install -y 
    git 
    unzip 
    libzip-dev 
    libpng-dev 
    libjpeg62-turbo-dev 
    libfreetype6-dev 
    libonig-dev 
    libxml2-dev 
    && docker-php-ext-install 
    pdo_mysql 
    mbstring 
    exif 
    pcntl 
    bcmath 
    gd 
    zip 
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN php artisan optimize

ENV SERVER_NAME=:${PORT}

EXPOSE 8080
