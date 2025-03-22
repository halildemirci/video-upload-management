FROM php:8.2-fpm

# Sistem bağımlılıkları
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    ffmpeg \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Composer kurulumu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Proje dizini
WORKDIR /var/www

# PHP upload limiti
RUN echo "upload_max_filesize=200M\npost_max_size=200M" > /usr/local/etc/php/conf.d/uploads.ini

# Uygulama dosyalarını kopyala
COPY . .

# SQLite dosyası (eğer kullanıyorsan)
RUN mkdir -p database && touch database/database.sqlite

# Composer ve Laravel işlemleri
RUN composer install --no-dev --optimize-autoloader

# ✅ Vite build adımı
RUN npm install && npm run build

# Laravel ayarları
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear
RUN php artisan storage:link || true
RUN php artisan migrate --force || true

# Port aç
EXPOSE 8080

# Uygulama başlat
CMD php -d variables_order=EGPCS -S 0.0.0.0:8080 -t public
