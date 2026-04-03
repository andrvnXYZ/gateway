# Gamit og PHP 8.2 with Apache
FROM php:8.2-apache

# I-install ang gikinahanglan nga extensions (Gi-fix ang syntax)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo_mysql

# I-enable ang Apache Rewrite Module para sa Lumen/Laravel routes
RUN a2enmod rewrite

# I-set ang Document Root sa 'public' folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/目录!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# I-copy ang imong tibuok project
COPY . /var/www/html

# I-install ang Composer (Gikan sa official image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Daganon ang composer install sa sulod sa container
RUN composer install --no-interaction --optimize-autoloader

# I-set ang permissions para dili ma-forbidden
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true