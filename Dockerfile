FROM php:8.4-cli-alpine

# Install git, unzip and system tools
RUN apk add --no-cache curl git unzip bash nodejs npm

# Install PHP extensions using official pre-compiled installer script
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Build frontend assets and vendor dependencies
RUN npm install && npm run build
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Create storage symlink and permissions
RUN php artisan storage:link || true
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
