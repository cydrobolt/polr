Polr
==================

###A beautiful, modern, fast, minimalist, and open-source URL shortening platform in vanilla PHP. 

######Semi-stable 1.1.0: Clone me!
######Beta 1.0.4 *broken*: https://github.com/Cydrobolt/polr/archive/1.0.0-b-d.zip

######Stable 0.2.3: https://github.com/Cydrobolt/polr/releases/download/v0.23-Alpha/polr-0.23-alpha.zip



Polr uses Semantic Versioning http://semver.org/

Do you use Polr? We'll add your website to the list at the bottom of the page, just email one of the collaborators, or support@polr.me
No attribution is required, and we encourage you to modify Polr, but it would be nice for you to link to us in footers, source, or about page. Thanks for trying Polr!

Useful Links:

 - http://github.com/Cydrobolt/polr-cli Command-line interface for Polr
 - http://github.com/Cydrobolt/polr-PaaS Polr Platform-as-a-Service (http://polr.cf)


==================
Installation
==================

 - Unpack Polr, or clone the git repo. Only the `git clone` option allows quick updating through `git pull`.
 - Go to the root of your Polr folder (on webserver)
 - Read each setup item carefully, and then click "create config"
 - *MySQL warning: Set your character set to UTF8. Some character sets are vulnerable to a certain bug in MySQL and mysqli_real_escape_string. For more information, please see http://stackoverflow.com/questions/5741187/sql-injection-that-gets-around-mysql-real-escape-string*. This bug should be mostly fixed >1.1.0. 
 - You're ready to go! Check back for updates, and `git pull` if possible to update Polr. Otherwise, you can download a ZIP from Github and replace your current files. Make sure to keep your `config.php`!

Prerequisites:

- mod_rewrite (install help: https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite)
- MySQL or MariaDB equivalent >= 5.5
- PHP >= 5.4
- Apache httpd
- MySQLi extension for PHP
- MySQLnd (native driver; i.e php5-mysql on Ubuntu)
- MCrypt (http://www.php.net//manual/en/book.mcrypt.php)

This list may be long, but most hosts include these prerequisites, so you don't have to install them yourself.

==================
Troubleshooting
==================

###I get a blank page at the user dashboard (/admin/)

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

This usually occurs when you are missing an extension, and thus, the expected associative arrays (e.g `mysqli_fetch_assoc`) are instead strings, and errors occur. The most common cause of this is the missing MySQLi extension or native driver. Refer to the first FAQ, or the `mysqli_fetch_all` FAQ. This issue is usually caused by an
old PHP version.

###The links produced give me 404

You need mod_rewrite in order to use Polr. Please take a look at https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite

###I can't run setup, I get various MySQL errors.

Make sure your host is correct. Some webhosts require you to bind to a certain ip or port, such as 0.0.0.0. You may need to modify the code if you need to use an unconventional port. (option coming soon)

Make sure the database is premade, and that the user has the required permissions to create tables.

###I'm getting an error in the dashboard; Missing mysqli_fetch_all.

This problem occurs if your PHP version is below 5.3 or you do not have the native driver (http://php.net/manual/en/book.mysqlnd.php). If you cannot install the native driver, place this in your `req.php` file:

```
function mysqli_fetch_all($res) {
    $array = array();
    while ($row = $res->fetch_assoc()) {
        array_push($array, $row);
    }
    return $array;
}
```


==================
####Current State: 1.0.4 Beta (download at releases) & 0.2.3 Alpha (latest semi-stable)

Welcome to Polr, the self-hosted version. Read up on some documentation through our github wiki (https://github.com/Cydrobolt/polr/wiki)

Would like to contribute? Submit pull requests through our Github page. Found an issue? Create an issue here: (https://github.com/Cydrobolt/polr/issues)

==================
######List of websites using Polr

- http://polr.me

####License


    Copyright (C) 2014 Chaoyi Zha <summermontreal@gmail.com>

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
