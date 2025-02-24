FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    zip \
    stockfish

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

CMD ["php", "cli/wss.php"]
