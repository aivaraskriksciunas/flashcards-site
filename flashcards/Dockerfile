
FROM php:8.2-cli

RUN docker-php-ext-install \
    bcmath \
    pdo_mysql

RUN apt update -y && apt install -y \
    npm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www
WORKDIR /var/www

EXPOSE 8000

CMD [ "php", "artisan", "serve", "--host", "0.0.0.0" ]
