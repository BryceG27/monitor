FROM php:8.3-fpm-alpine

# Add system dependencies (Alpine è una distribuzione molto leggera)
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    git \
    bash \
    nginx \
    curl \
    icu-dev \
    libxml2-dev \
    libxslt-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql bcmath intl soap xsl

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Create the working directory
WORKDIR /var/www

COPY . .

# Set laravel permits
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install composer dependencies
RUN composer install
