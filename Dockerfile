ARG PHP_VERSION=7.1
FROM php:${PHP_VERSION}

ENV COMPOSER_HOME=/var/lib/composer
WORKDIR /opt/app

RUN apt-get update -y && \
    apt-get install -y git zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev && \
    pecl install xdebug-2.5.0 && \
    docker-php-ext-install -j$(nproc) zip gd && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-enable xdebug && \
    php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" && \
    php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); } echo PHP_EOL;" && \
    php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer && \
    rm -rf /tmp/composer-setup.php

ENTRYPOINT ["./build.sh" ]
