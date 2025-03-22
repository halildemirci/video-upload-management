FROM php:8.2-fpm

# Sistem paketlerini yükle
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    ffmpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Composer kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel çalışma klasörü
WORKDIR /var/www

# Upload limitlerini artır
RUN echo "upload_max_filesize=200M\npost_max_size=200M" > /usr/local/etc/php/conf.d/uploads.ini

# Dosyaları kopyala
COPY . .

# Laravel bağımlılıklarını yükle
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# Storage link
RUN php artisan storage:link

# Port ayarı
EXPOSE 8080

# Başlatma komutu
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
