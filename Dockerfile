FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy Apache configuration file
COPY apache.conf /etc/apache2/sites-available/000-default.conf