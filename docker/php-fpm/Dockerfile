FROM php:7.4-fpm

# RUN apt-get update && apt-get install -y mariadb-client
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql
# or default-mysql-client

# ADD docker/php-fpm/php.ini /usr/local/etc/php/php.ini

COPY ./ /var/www/html/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
