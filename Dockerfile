FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    curl \
    gnupg \
    nano

# Configure and install GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

# Install other extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath intl zip opcache

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (Vite support)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs

# Buat folder jika belum ada
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache

# Ubah kepemilikan dan hak akses (Permission)
# Catatan: PHP-FPM juga menggunakan user 'www-data', jadi ini tetap aman
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose port 9000 (Ini port bawaan PHP-FPM, BUKAN port 80)
EXPOSE 9000

# Jalankan PHP-FPM
CMD ["php-fpm"]