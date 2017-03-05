# Installation
-----------------

This installation guide will help you install Polr 2.0, the latest iteration of Polr.

## Server Requirements

The following software is required on your server to run Polr 2.0.
In the case that you cannot fulfill the following requirements (e.g free shared hosting),
you may be interested in looking at a [legacy 1.x release](https://github.com/cydrobolt/polr/releases) of Polr (now unsupported).


 - Apache, nginx, IIS, or lighttpd (Apache preferred)
 - PHP >= 5.5.9
 - MariaDB or MySQL >= 5.5, SQLite alternatively
 - composer
 - PHP requirements:
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - PDO MySQL Driver (php5-mysql on Debian & Ubuntu, php5x-pdo_mysql on FreeBSD)
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
    - JSON PHP Extension

## Downloading the source code

If you would like to download a stable version of Polr, you may check out [the releases page](https://github.com/cydrobolt/polr/releases).

```bash
$ sudo su
# switch to Polr directory (replace with other directory path if applicable)
$ cd /var/www
# clone Polr
$ git clone https://github.com/cydrobolt/polr.git --depth=1
# set correct permissions
$ chmod -R 755 polr

# if you would like to use a specific release, check out
# the tag associated with the release. see link above.
$ # git checkout <tag>

# run only if on Ubuntu-based systems
$ chown -R www-data polr
# run only if on Fedora-based systems
$ chown -R apache polr

# run only if on recent Fedora, or other system, with SELinux enforcing
$ chcon -R -t httpd_sys_rw_content_t polr/storage polr/.env
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

To run Polr on Apache, you will need to create a new Apache configuration file in your operating system's Apache configuration folder (e.g `/etc/apache2/sites-enabled` or `/etc/httpd/sites-enabled`) or add a virtual host to your `httpd-vhosts.conf` file like so:

Replace `example.com` with your server's external address and restart Apache when done.

```apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias example.com

    DocumentRoot "/var/www/polr/public"
    <Directory "/var/www/polr/public">
        Require all granted
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
### nginx

Replace `example.com` with your server's external address. You will need to install `php5-fpm`:

```
$ sudo apt-get install php5-fpm
```

Useful LEMP installation tutorial by [DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-12-04)

```nginx
# Upstream to abstract backend connection(s) for php
upstream php {
    server unix:/var/run/php-fpm.sock;
    server 127.0.0.1:9000;
}

# HTTP

server {
    listen       *:80;
    root         /var/www/polr/public;
    index        index.php index.html index.htm;
    server_name  example.com; # Or whatever you want to use

#   return 301 https://$server_name$request_uri; # Forces HTTPS, which enables privacy for login credentials.
                                                 # Recommended for public, internet-facing, websites.

    location / {
            try_files $uri $uri/ /index.php$is_args$args;
            # rewrite ^/([a-zA-Z0-9]+)/?$ /index.php?$1;
    }

    location ~ \.php$ {
            try_files $uri =404;
            include /etc/nginx/fastcgi_params;

            fastcgi_pass    php;
            fastcgi_index   index.php;
            fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param   HTTP_HOST       $server_name;
    }
}


# HTTPS

#server {
#   listen              *:443 ssl;
#   ssl_certificate     /etc/ssl/my.crt;
#   ssl_certificate_key /etc/ssl/private/my.key;
#   root                /var/www/polr/public;
#   index index.php index.html index.htm;
#   server_name         example.com;
#
#   location / {
#           try_files $uri $uri/ /index.php$is_args$args;
#           # rewrite ^/([a-zA-Z0-9]+)/?$ /index.php?$1;
#   }
#
#   location ~ \.php$ {
#           try_files $uri =404;
#           include /etc/nginx/fastcgi_params;
#
#           fastcgi_pass    php;
#           fastcgi_index   index.php;
#           fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
#           fastcgi_param   HTTP_HOST       $server_name;
#   }
#}
```
### Shared hosting/other

To run Polr on another HTTP server or on shared hosting, you will need to set the home
directory to `/PATH_TO_POLR/public`, not the root Polr folder.

## Create necessary databases

### MySQL

You must create a database for Polr to use before you can complete the setup script.
To create a database for Polr, you can log onto your `mysql` shell and run the following command:

```sql
CREATE DATABASE polrdatabasename;
```

Remember this database name, as you will need to provide it to Polr during setup.
Additionally, if you wish to create a new user with access to solely this database, please look into MySQL's [GRANT](https://dev.mysql.com/doc/refman/5.7/en/grant.html) directive.

### SQLite

You may also use SQLite in place of MySQL for Polr. However, SQLite is not recommended for use with Polr.


## Option 1: Run automatic setup script

Once your server is properly set up, you will need to configure Polr and
enable it to access the database.

Copy the `.env.setup` file to `.env` in your website's root directory.

`$ cp .env.setup .env`

Then, head over to your new Polr instance, at the path `/setup/` to configure
your instance with the correct information.

This will automatically create your databases and write a new configuration file to disk, `.env`. You may make changes later on by editing this file.

You should be set. You may go back to your Polr homepage and log in to perform
any other actions.

## Option 2: Write configuration and database manually

If you wish to configure and initialize Polr manually, you may do so using
your command line.

Rename copy `resources/views/env.blade.php` to `.env` at the root directory
and update the values appropriately. Do not leave any curly braces in your new `.env`. You may leave
certain sections empty to use the defaults.

You may then run the following `artisan` command to populate the database.
You will also need to insert a admin user into the `users` table through `mysql` or a graphical `sql` interface.

```bash
php artisan migrate --force
php artisan geoip:update
```

This should create the necessary databases.
