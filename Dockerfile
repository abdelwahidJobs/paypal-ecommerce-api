FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

WORKDIR /var/www/stripe-payments-api

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/stripe-payments-api \
    && chmod -R 755 /var/www/stripe-payments-api/storage \
    && chmod -R 755 /var/www/stripe-payments-api/bootstrap/cache

RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf \
    && sed -i 's!/var/www/html!/var/www/stripe-payments-api/public!g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/stripe-payments-api/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# This ensures Apache starts (inherited from base image)
CMD ["apache2-foreground"]