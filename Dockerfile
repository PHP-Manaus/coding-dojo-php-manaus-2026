FROM php:8.4-cli-alpine

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/tmp/composer \
    COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

CMD ["vendor/bin/phpunit"]
