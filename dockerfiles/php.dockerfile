FROM php:8.0.8-fpm-alpine

ENV PHPGROUP=laravel
ENV PHPUSER=laravel

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/user = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/public

# Setup GD extension
RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
    freetype \
    freetype-dev \
    libpng \
    libpng-dev \
    libjpeg \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    jpeg-dev \
    libmcrypt-dev \
    libzip-dev
RUN docker-php-ext-configure gd --enable-gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ 
RUN docker-php-ext-install zip gd

# Install dom extension
RUN apk update \
    && apk upgrade \
    && apk add libxml2-dev
RUN docker-php-ext-install dom

# Install mbstring extension
RUN apk add --no-cache \
    oniguruma-dev \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mbstring \
    && rm -rf /tmp/*

RUN docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]