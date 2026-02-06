# Gunakan image resmi PHP dengan Apache
FROM php:8.2-apache

# Aktifkan ekstensi mysqli (untuk koneksi MySQL)
RUN docker-php-ext-install mysqli

# Copy semua source code ke container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Berikan permission agar Apache bisa akses file
RUN chown -R www-data:www-data /var/www/html

# Expose port Apache
EXPOSE 80
