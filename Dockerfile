FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
WORKDIR /var/www/html
COPY ./app .
EXPOSE 80
