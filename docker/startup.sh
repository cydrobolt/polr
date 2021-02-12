#!/bin/bash

# set up symlink even if .env doesnt exist yet
# but don't create it if it doesn't exist
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
# if it does exist
if [ -f /data/env ]; then
    chmod 770 /var/www/polr/.env
fi

chown root:www-data -R /data
chmod 770 -R /data


# finish up by starting supervisor in the foreground to keep the container running
/usr/bin/supervisord
