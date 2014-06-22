Polr
==================

###A beautiful, modern, minimalist, and open-source URL shortening platform.

==================
Installation
==================

 - Unpack Polr, or clone the git repo
 - Go to the root of your Polr folder (on webserver)
 - Read each setup item carefully, and then click "create config"
 - You're ready to go!

Prerequisites:

- mod_rewrite (install help: https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite)
- MySQL or MariaDB equivalent >= 5.5
- PHP >= 5.4
- Apache httpd
- MySQLi extension for PHP
- MySQLnd (native driver; i.e php5-mysql on Ubuntu)
- MCrypt (http://www.php.net//manual/en/book.mcrypt.php)

==================
Troubleshooting
==================

###I get a blank page at the user dashboard (ucp.php)

This error occurs when your PHP installation's version is older than 5.3, or because you do not have either the PHP mysqli extension or do not have the MySQLnd (native driver). On Ubuntu, you simply need to `sudo apt-get install php5-mysql` and restart apache2 `service apache2 restart`. You should have the mysqli.so extension enabled. Ask on IRC if you need further support.

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

###I have errors enabled, and I get "`Illegal String Offset`"

This usually occurs when you are missing an extension, and thus, the expected associative arrays (e.g `mysqli_fetch_assoc`) are instead strings, and errors occur. The most common cause of this is the missing MySQLi extension or native driver. Refer to the first FAQ.

###The links produced give me 404

You need mod_rewrite in order to use Polr. Please take a look at https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite

###I can't run setup, I get various MySQL errors.

Make sure your host is correct. Some webhosts require you to bind to a certain ip or port, such as 0.0.0.0. You may need to modify the code if you need to use an unconventional port. (option coming soon)

Make sure the database is premade, and that the user has the required permissions to create tables.

==================
####Current State: 0.20 Alpha (download at release)

Welcome to Polr, the self-hosted version. Read up on some documentation through our github wiki (https://github.com/Cydrobolt/polr/wiki)

Would like to contribute? Submit pull requests through our Github page. Found an issue? Create an issue here: (https://github.com/Cydrobolt/polr/issues)



    Copyright (C) 2014  Polr

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
