FROM dunglas/frankenphp:php8.4-bookworm

WORKDIR /app

RUN apt-get update && apt-get install -y `
    git unzip curl libzip-dev libpng-dev libjpeg62-turbo-dev `
    libfreetype6-dev libonig-dev libxml2-dev

RUN docker-php-ext-install `
    pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - `
    && apt-get install -y nodejs

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy Laravel application
COPY . .

# Build Vite
RUN npm run build

# Remove old Laravel config cache
RUN rm -f bootstrap/cache/config.php bootstrap/cache/*.php

# Caddy
COPY Caddyfile /etc/frankenphp/Caddyfile

EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
