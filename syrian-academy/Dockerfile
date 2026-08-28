FROM serversideup/php:8.2-fpm-nginx

WORKDIR /var/www/html

# نسخ المشروع مع استثناء storage
COPY . .

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --ignore-platform-reqs --no-scripts

# الصلاحيات - فقط bootstrap/cache
RUN mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/logs

EXPOSE 80

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]