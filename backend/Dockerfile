FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libzip-dev \
    libonig-dev libxml2-dev libjpeg-dev libpng-dev \
    libfreetype6-dev libcurl4-openssl-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./

RUN composer install --dev --no-interaction --optimize-autoloader --no-scripts

COPY . .

RUN mkdir -p var/cache var/log var/sessions \
    && chmod -R 777 var

EXPOSE 8000

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]