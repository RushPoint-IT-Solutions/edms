# syntax=docker/dockerfile:1.4

FROM php:7.4-fpm

ARG UID=1000
ARG GID=1000

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        openssh-client \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN groupmod -g "${GID}" www-data \
    && usermod -u "${UID}" -g "${GID}" www-data

COPY docker/php.ini /usr/local/etc/php/conf.d/99-edms.ini
COPY docker/entrypoint.sh /usr/local/bin/edms-entrypoint

RUN chmod +x /usr/local/bin/edms-entrypoint

COPY composer.json composer.lock ./

RUN mkdir -p -m 0700 /root/.ssh \
    && ssh-keyscan github.com >> /root/.ssh/known_hosts

RUN --mount=type=ssh --mount=type=secret,id=github_token,required=false \
    if [ -f /run/secrets/github_token ]; then \
        composer config --global github-oauth.github.com "$(cat /run/secrets/github_token)"; \
    fi \
    && composer install --no-interaction --prefer-dist --no-scripts --no-autoloader \
    && composer clear-cache \
    && rm -f /root/.composer/auth.json

COPY . .

RUN composer dump-autoload --no-interaction --optimize \
    && chown -R www-data:www-data vendor storage bootstrap/cache

USER www-data

ENTRYPOINT ["edms-entrypoint"]
CMD ["php-fpm"]
