FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    mariadb-client

# Configure and install GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd

# Install other PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath intl zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Set permissions for CodeIgniter writable directories
RUN mkdir -p /var/www/html/writable \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 9000
CMD ["php-fpm"]
