#!/bin/bash

cd /var/www/polr/

if ! [ -f /data/env ]; then
    cat /var/www/polr/.env.setup > /data/env
fi

ln -s /data/env .env
ln -s /data/storage /var/www/polr/.

mkdir -p /data/storage/app
mkdir -p /data/storage/logs
mkdir -p /data/storage/framework/cache
mkdir -p /data/storage/framework/sessions
mkdir -p /data/storage/framework/views

# Set permissions/ownership
chown root:www-data -R /var/www/polr
chmod 750 -R /var/www/polr

# The website needs to be able to update the .env file.
chmod 770 /var/www/polr/.env

chown root:www-data -R /data
chmod 770 -R /data


# finish up by starting supervisor in the foreground to keep the container running
/usr/bin/supervisord
