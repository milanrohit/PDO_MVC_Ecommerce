FROM php:8.2-apache

# Install PDO and MySQL extensions
RUN docker-php-ext-install \ pdo \ pdo_mysql \ mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your app files
COPY . /var/www/html/

# Set the working directory
EXPOSE 80