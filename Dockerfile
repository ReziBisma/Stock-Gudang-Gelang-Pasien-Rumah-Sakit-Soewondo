# Stage 1: Builder untuk menginstall dependensi composer
FROM composer:2.6 AS builder
WORKDIR /app
# Copy file composer terlebih dahulu
COPY composer.json composer.lock ./
# Install dependensi (ignore platform reqs agar tidak bergantung pada ekstensi di image composer)
RUN composer install --no-dev --ignore-platform-reqs --no-interaction --no-scripts
# Copy sisa source code
COPY . .
# Jalankan dump-autoload untuk optimasi
RUN composer dump-autoload --optimize

# Stage 2: Image Production
FROM php:8.2-apache

# Aktifkan ekstensi mysqli (untuk koneksi MySQL) dan zip (biasa dibutuhkan phpspreadsheet)
RUN apt-get update && apt-get install -y libzip-dev zip && \
    docker-php-ext-install mysqli zip && \
    rm -rf /var/lib/apt/lists/*

# Copy semua file dari stage builder ke dalam directory apache
COPY --from=builder /app /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Berikan permission agar Apache bisa akses file
RUN chown -R www-data:www-data /var/www/html

# Expose port Apache
EXPOSE 80
