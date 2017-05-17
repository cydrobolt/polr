FROM ubuntu:16.04

# Update
RUN apt-get update && apt-get dist-upgrade -y

# Install the necessary packages.
RUN apt-get install -y \
    supervisor apache2 git php7.0 libapache2-mod-php7.0 php7.0-cli \
    php7.0-mysqlnd php7.0-xml php7.0-mbstring php7.0-curl php7.0-zip

RUN a2enmod php7.0
RUN a2enmod rewrite

# Add the files to the container.
RUN mkdir /var/www/polr
COPY . /var/www/polr/

# Put the apache configuration in place
RUN mv /var/www/polr/docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Configure supervisor to keep apache running.
RUN mv /var/www/polr/docker/supervisor.conf /etc/supervisor/conf.d/apache.conf

# Install and run composer to add necessary packages. Personally, I would prefer it if vendor was
# bundled with releases, and not have this section
RUN apt-get install curl  -y
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer
WORKDIR /var/www/polr
RUN composer install

# Set permissions/ownership
RUN chmod -R 750 /var/www/polr
RUN chmod -R 770 /var/www/polr/storage
RUN chmod 770 /var/www/polr/.env
RUN chown -R root:www-data /var/www/polr

# Use supervisor as the foreground process keeping the container running.
CMD ["/usr/bin/supervisord"]



