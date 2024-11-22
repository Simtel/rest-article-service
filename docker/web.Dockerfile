FROM php:8.3-fpm-alpine

RUN apk --no-cache add shadow sudo

RUN apk update && apk add --no-cache \
    $PHPIZE_DEPS \
    bash \
    libmcrypt-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    openssl \
    unzip \
    vim \
    wget \
    libxml2-dev \
    zip

RUN docker-php-ext-install \
    bcmath \
    gd \
    pdo \
    mysqli \
    pdo_mysql \
    xml \
    zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# fix this issue https://ask.fedoraproject.org/t/sudo-setrlimit-rlimit-core-operation-not-permitted/4223
RUN echo "Set disable_coredump false" >> /etc/sudo.conf

# Clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*
RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html

USER www-data
