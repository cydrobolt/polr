# Installation
-----------------

This installation guide will help you install Polr 2.0, the latest iteration of Polr.

## Server Requirements

The following software is required on your server to run Polr 2.0.
In the case that you cannot fulfill the following requirements (e.g free shared hosting),
you may be interested in looking at a [legacy 1.x release](https://github.com/cydrobolt/polr/releases) of Polr (now unsupported).

 - Apache, nginx, IIS, or lighttpd (Apache preferred)
 - PHP >= 5.5.9
 - MariaDB >= 10.0 or MySQL >= 5.5, SQLite alternatively
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

To run Polr on Apache, you will need to add a virtual host to your
`httpd-vhosts.conf` like so:

Replace `example.com` with your server's external address.

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
### nginx

Replace `example.com` with your server's external address. You will need to install `php5-fpm`:

```
$ sudo apt-get install php5-fpm
```

Useful LEMP installation tutorial by [DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-12-04)

```nginx
# Upstream to abstract backend connection(s) for php
upstream php {
    server unix:/tmp/php-cgi.socket;
    server 127.0.0.1:9000;
}

server {
    server_name example.com; # Or whatever you want to use
    listen 80 default;
    rewrite ^(.*) https://example.com$1 permanent;
}

# HTTPS server (optional)

# server {
#     listen 443;
#     server_name example.com;
#
#     root /var/vhost/example.com/public;
#     index index.php;
#
#     ssl on;
#     ssl_certificate /etc/ssl/crt/example.com.crt; # Or wherever your crt is
#     ssl_certificate_key /etc/ssl/key/example.com.key; # Or wherever your key is
#     ssl_session_timeout 5m;
#
#     ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
#     ssl_prefer_server_ciphers on;
#     ssl_ciphers "ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:AES:CAMELLIA:DES-CBC3-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!aECDH:!EDH-DSS-DES-CBC3-SHA:!EDH-RSA-DES-CBC3-SHA:!KRB5-DES-CBC3-SHA";
#     ssl_buffer_size 1400; # 1400 bytes, within MTU - because we generally have small responses. Could increase to 4k, but default 16k is too big
#
#     location / {
#         add_header Strict-Transport-Security max-age=15768000;
#         try_files $uri /index.php$is_args$args;
#     }
#
#     location ~ \.php$ {
#         include fastcgi_params;
#         fastcgi_pass unix:/var/run/php5-fpm.sock;
#         fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
#         fastcgi_index index.php;
#         fastcgi_keep_conn on;
#         add_header Strict-Transport-Security max-age=15768000;
#     }
# }
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
Additionally, if you wish to create a new user with access to soloely this database, please look into MySQL's [GRANT](https://dev.mysql.com/doc/refman/5.7/en/grant.html) directive.

### SQLite

You may also use SQLite in place of MySQL for Polr. However, SQLite is not recommended for use with Polr.


## Option 1: Run automatic setup script

Once your server is properly set up, you will need to configure Polr and
enable it to access the database.

Head over to your new Polr instance, at the path `/setup/` to configure
your instance with the correct information.

This will automatically create your databases and write a configuration file to disk, `.env`. You may make changes later on by editing this file.

You should be set. You may go back to your Polr homepage and log in to perform
any other actions.

## Option 2: Write configuration and database manually

If you wish to configure and initialize Polr manually, you may do so using
your command line.

Rename `.env.example` to `.env` and update the values appropriately.
You may then run the following `artisan` command to populate the database.
You will also need to insert a admin user into the `users` table through `mysql`
or a graphical `sql` interface.

```bash
php artisan migrate
```

This should create the necessary databases.
