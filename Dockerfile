# This Dockerfile uses multi-stage builds to keep the number of layers down.
# This feature requires Docker version 17.05 or higher.

# ------------------------------------------------------------------------------
# First build stage: Load Polr dependencies.

FROM php:7.2-apache as build

RUN apt-get update && apt-get install -y --no-install-recommends git unzip

# Install Composer (https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md)
RUN EXPECTED_SIGNATURE="$(curl -sS https://composer.github.io/installer.sig)"; \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    ACTUAL_SIGNATURE="$(php -r "echo hash_file('SHA384', 'composer-setup.php');")"; \
    if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then \
        >&2 echo 'ERROR: Invalid installer signature'; \
        rm composer-setup.php; \
        exit 1; \
    fi; \
    php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer; \
    RESULT=$?; \
    rm composer-setup.php; \
    exit $RESULT

ENV COMPOSER_ALLOW_SUPERUSER=1

# Run composer before copying remaining project files to leverage caching
COPY ./composer.* /polr/
WORKDIR /polr
RUN composer install --prefer-dist --no-dev --no-autoloader

# Copy project files and re-run composer to finish installation
COPY ./ /polr/
RUN composer install --no-dev --optimize-autoloader

# Use default .env file, values are be overridden by environment variables
RUN mv ./.env.setup ./.env

RUN php artisan geoip:update

# Cleanup
RUN rm composer.* && rm -rf deploy


# ------------------------------------------------------------------------------
# Second build stage: Startover and use the Polr files created in first stage

FROM php:7.2-apache

LABEL maintainer="The Polr Community, https://polrproject.org/"
LABEL description="Polr URL Shortener"

# Configure Apache and PHP
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_SERVER_NAME localhost

RUN docker-php-ext-install pdo_mysql && \
    a2enmod rewrite && \
    sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "ServerTokens Prod\n" >> /etc/apache2/apache2.conf && \
    echo "ServerSignature Off\n" >> /etc/apache2/apache2.conf && \
    echo "ServerName ${APACHE_SERVER_NAME}" >> /etc/apache2/apache2.conf && \
    mv ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

# Fetch Polr files from first stage
COPY --from=build --chown=www-data:www-data /polr ${APACHE_DOCUMENT_ROOT}/../

# Improve performance by setting AllowOverride None
RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>" > /etc/apache2/sites-enabled/polr.conf && \
    echo "AllowOverride None" >> /etc/apache2/sites-enabled/polr.conf && \
    echo "Options -Indexes +FollowSymLinks" >> /etc/apache2/sites-enabled/polr.conf && \
    cat ${APACHE_DOCUMENT_ROOT}/.htaccess >> /etc/apache2/sites-enabled/polr.conf && \
    echo "</directory>" >> /etc/apache2/sites-enabled/polr.conf && \
    rm ${APACHE_DOCUMENT_ROOT}/.htaccess
