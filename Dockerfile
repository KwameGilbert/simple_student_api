# Use official PHP image with Apache
FROM php:8.0-apache

# Enable Apache modules (e.g. rewrite)
RUN a2enmod rewrite

# Copy application source into Apache's document root
COPY public/ /var/www/html/
COPY src/ /var/www/html/src/
COPY data/ /var/www/html/data/
COPY composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
