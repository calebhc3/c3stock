FROM php:8.4-fpm

# Instala dependências essenciais
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring xml pdo pdo_mysql zip dom

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia todo o código primeiro para evitar erro com artisan
COPY . .

# Ajusta permissões da pasta storage e bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Instala as dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Expor a porta usada pelo PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
