FROM node:20 as build_front

COPY package.json .
COPY package-lock.json .
COPY vite.config.js .
RUN npm ci
COPY assets_vite assets_vite
RUN mkdir -p public/build
RUN npm run build

##############################################################################################$

FROM dunglas/frankenphp as build_php

RUN apt-get update && apt-get install -y git zip unzip

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

COPY bin bin
COPY config config
COPY resources resources
COPY src src
COPY templates templates
COPY composer.json .
COPY composer.lock .
COPY .env .env
COPY public public

ENV APP_ENV="prod"
RUN composer install
RUN php bin/console cache:clear --env=prod --no-debug

##############################################################################################$

FROM dunglas/frankenphp

RUN install-php-extensions \
    intl \
    zip \
    opcache

COPY --from=build_php /app /app
COPY --from=build_front /public/build /app/public/build

ENV APP_ENV="prod"
ENV VITE_DEV="false"

ENV FRANKENPHP_CONFIG="worker ./public/index.php"
ENV CADDY_GLOBAL_OPTIONS="auto_https off"
ENV SERVER_NAME=":80"
ENV APP_RUNTIME="Runtime\\FrankenPhpSymfony\\Runtime"
