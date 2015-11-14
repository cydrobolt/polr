# Installation
-----------------

This installation guide will help you install Polr 2.0, the latest iteration of Polr.

## Server Requirements

The following software is required on your server to run Polr 2.0.
In the case that you cannot fulfill the following requirements (e.g free shared hosting),
you may be interested in looking at a [legacy 1.x release](https://github.com/cydrobolt/polr/releases) of Polr (now unsupported).

 - Apache, nginx, IIS, or lighttpd (Apache preferred)
 - PHP >= 5.5.9
 - MariaDB >= 10.0 or MySQL >= 5.5
 - Composer (optional)
 - PHP requirements:
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension

## Downloading the source code

```bash
$ sudo su
$ cd /var/www
$ git clone https://github.com/cydrobolt/polr.git
$ cd polr

$ git checkout 2.0-dev
```

## Installing using `composer`

```bash
# download composer package
curl -sS https://getcomposer.org/installer | php
# update/install dependencies
php composer.phar install --no-dev -o
```

## Running Polr on...

### Apache

To run Polr on Apache, which is the recommended method to run Polr,
you will need to add a virtual host to your `httpd-vhosts.conf` like so:

```apache
<VirtualHost *:80>
    ServerName example.com # Your external address
    ServerAlias example.com # Make this the same as ServerName
    DocumentRoot "/var/www/polr/public"
    <Directory "/var/www/polr/public">
        Require all granted # Used by Apache 2.4
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

If `mod_rewrite` is not already enabled, you will need to enable it like so:

```bash
# enable mod_rewrite
a2enmod rewrite
# restart apache on Ubuntu
# sudo service apache2 restart
# restart apache on Fedora/CentOS
# sudo service httpd restart
```

## Run setup script

Once your server is properly set up, you will need to configure Polr and
enable it to access the database.

Head over to your new Polr instance, and head over to `/setup/` to configure
your instance with the correct information.

## Create database tables

Once your instance is fully configured, head into your terminal to
create the needed database tables.

```bash
php artisan migrate
```
