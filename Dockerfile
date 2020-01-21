FROM php:7.3-cli

RUN docker-php-ext-install pdo pdo_mysql

