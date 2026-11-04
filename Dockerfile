FROM php:8.4-cli-alpine

# Install git, unzip and system tools
RUN apk add --no-cache curl git unzip bash nodejs npm ca-certificates

# Install PHP extensions using official pre-compiled installer script
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Configure PHP upload and execution limits for creative assets
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 120M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Build frontend assets and vendor dependencies
RUN npm install && npm run build
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Create storage directories and set permissions
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs \
    && php artisan storage:link || true \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 10000

CMD ["sh", "-c", "mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs && chmod -R 777 storage bootstrap/cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
