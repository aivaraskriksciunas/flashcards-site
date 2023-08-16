
FROM php:8.2-fpm-alpine

RUN docker-php-ext-install \
    bcmath \
    pdo_mysql \
    opcache

COPY production/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN apk add --no-cache \
    npm \
    linux-headers

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./production/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . /var/www
WORKDIR /var/www 

RUN composer update
RUN npm install && npx webpack build --mode production

