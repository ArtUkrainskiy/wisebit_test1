FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    autoconf \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=no" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

CMD ["php", "--version"]
