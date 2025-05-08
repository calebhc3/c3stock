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
    && docker-php-ext-install gd mbstring xml pdo pdo_mysql zip dom bcmath

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia todo o código para dentro da imagem
COPY . .

# Garante que as pastas de cache existam (evita erro "Please provide a valid cache path")
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap \
    && chmod -R 775 storage bootstrap

# Instala as dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Expor a porta usada pelo PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
