FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    default-mysql-client \
    nginx \
    supervisor \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    intl \
    pcntl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/nginx/default.conf /etc/nginx/sites-enabled/default

# Create supervisor config for running both PHP-FPM and Nginx
RUN mkdir -p /etc/supervisor/conf.d
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Make scripts executable
RUN chmod +x railway/*.sh

# Laravel needs write access for runtime caches / logs.
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 8080 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
