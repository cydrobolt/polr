FROM php:7.2-fpm-alpine
RUN apk add --update --no-cache \
    curl-dev curl nginx supervisor && \
    mkdir /run/nginx

RUN docker-php-ext-install curl pdo pdo_mysql mbstring tokenizer json

RUN mkdir -p /usr/src/app && chown www-data:www-data /usr/src/app/
WORKDIR /usr/src/app
COPY ./ /usr/src/app/
COPY .env.setup /usr/src/app/.env

USER www-data
RUN curl -sS https://getcomposer.org/installer | php && php composer.phar install --no-dev -o

USER root
RUN chown www-data:www-data /usr/src/app -R

COPY ./deploy/nginx.conf /etc/nginx/nginx.conf
COPY ./deploy/supervisord.ini /etc/supervisor.d/supervisord.ini

EXPOSE 80
# APP_ADDRESS is env for nginx server_name
CMD sed -i "s/server_name localhost;/server_name ${APP_ADDRESS:-localhost};/g" /etc/nginx/nginx.conf && \
supervisord --nodaemon --pid /var/run/supervisord.pid --configuration /etc/supervisord.conf
