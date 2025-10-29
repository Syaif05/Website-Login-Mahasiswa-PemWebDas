FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

RUN apt-get clean && rm -rf /var/lib/apt/lists/*