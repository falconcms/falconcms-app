FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip nginx supervisor \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libxml2-dev libonig-dev libpq-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring gd zip exif pcntl bcmath xml intl \
    && docker-php-ext-enable opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy app
COPY . .

# Minimal .env so artisan can boot during build (no real DB needed for vendor:publish)
RUN echo "APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=" > .env \
    && echo "APP_ENV=production" >> .env \
    && echo "DB_CONNECTION=sqlite" >> .env \
    && echo "DB_DATABASE=/tmp/build.sqlite" >> .env \
    && touch /tmp/build.sqlite

# Create required storage directories before composer install (artisan package:discover needs them)
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions \
             storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install PHP deps — triggers vendor:publish for falcon themes + assets
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction

# Publish vendor assets into public/vendor/
RUN php artisan vendor:publish --all --force

# Build frontend assets
RUN npm install && npm run build

# Remove build .env — real values come from Render env vars at runtime
RUN rm .env

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && git config --global --add safe.directory /var/www/html \
    && mkdir -p /var/www/.composer/cache \
    && chown -R www-data:www-data /var/www/.composer

# Configs
COPY docker/nginx.conf /etc/nginx/sites-enabled/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
