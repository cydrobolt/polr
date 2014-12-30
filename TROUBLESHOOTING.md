Troubleshooting
==================

###I get a blank page at the user dashboard (/admin/)

This error occurs when your PHP installation's version is older than 5.3, or because you do not have either the PHP mysqli extension or do not have the MySQLnd (native driver). On Ubuntu, you simply need to `sudo apt-get install php5-mysqlnd` and restart apache2 `service apache2 restart`. You should have the mysqli.so extension enabled. Ask on IRC if you need further support.

###I get a blank page when trying to register (registerproc.php)

This may mean you do not have the MCrypt extension for PHP. To install this on Ubuntu, run the following commands:

```
sudo apt-get install php5-mcrypt
sudo mv -i /etc/php5/conf.d/mcrypt.ini /etc/php5/mods-available/
sudo php5enmod mcrypt
sudo service apache2 restart
```

On Fedora/Centos:

```
yum update
yum install php-mcrypt*
sudo php5enmod mcrypt
sudo service apache2 restart
```

###The links produced give me 404

You need mod_rewrite in order to use Polr. Please take a look at https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite

###I can't run setup, I get various MySQL errors.

Make sure your host is correct. Some webhosts require you to bind to a certain ip or port, such as 0.0.0.0. You may need to modify the code if you need to use an unconventional port. (option coming soon)

Make sure the database is premade, and that the user has the required permissions to create tables.

###I can't install Polr through the setup script.

You probably didn't configure MySQL correctly. Delete `config.php` and try again.
