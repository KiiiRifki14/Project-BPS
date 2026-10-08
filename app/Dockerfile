FROM php:8.2-cli

# Install system dependencies & PHP extensions required for Laravel & Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql bcmath zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set write permissions for storage & cache
RUN chmod -R 777 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Command to run Laravel application on Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
