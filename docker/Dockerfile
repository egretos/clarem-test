FROM php:7.4-fpm
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


RUN apt-get update && apt install zip unzip php-zip && apt-get install -y \
        curl \
        wget \
        git \
    && docker-php-ext-install mcrypt mbstring zip \

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod 777 /var/www/cache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
EXPOSE 9000
CMD ["php-fpm"]