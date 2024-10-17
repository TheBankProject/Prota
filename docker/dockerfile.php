FROM php:8.1-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

WORKDIR /var/www

COPY ./app ./app
COPY ./vendor ./vendor
COPY ./replace_env/.env ./app/.env

CMD [ "php", "-S", "0.0.0.0:8000", "-t", "/var/www/app/Public" ]