Polr
==================

###A beautiful, modern, fast, minimalist, and open-source URL shortening platform in PHP. 
Polr is an enterprise-class open-source link shortening web application designed to operate at many scales, built on the Software-as-a-Service paradigm. It allows you to host your own URL shortener, to brand your URLs, and to gain control over your data. Polr is especially easy to use, and provides a modern, themable feel. 

- **Latest Development Version: Clone this repo**
- **Latest Release: https://github.com/Cydrobolt/polr/releases**
- **[Troubleshooting information](https://github.com/Cydrobolt/polr/blob/master/TROUBLESHOOTING.md)**
- **[Screenshots](http://imgur.com/a/BheDx)**
- **[Vote for us on Bitnami](https://bitnami.com/stack/polr/)**


Polr uses Semantic Versioning http://semver.org/

Recommended Installation Method
==================
Using OpenShift by Red Hat to host your instance of Polr for free is highly recommended. You can instantly deploy an instance of Polr by using this quickstart: https://hub.openshift.com/quickstarts/180-polr


Manual Installation
==================

 - Unpack Polr, or clone the git repo. Only the `git clone` option allows quick updating through `git pull`.
 - Go to the root of your Polr folder (on webserver)
 - Read `INSTALL.txt`
 - You're ready to go! Check back for updates, and `git pull` if possible to update Polr. Otherwise, you can download a ZIP from Github and replace your current files. Make sure to keep your `config.php`!
 - Note: *please* disable errors on your server if you plan to use Polr in production. Certain warnings are normal, and you should not panic. It is not only unsafe but having errors shown also clutters the interface.

Prerequisites:

- mod_rewrite (install help: https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite)
- MySQL or MariaDB equivalent >= 5.5
- PHP >= 5.3
- Apache httpd or nginx (no out-of-box compatibility with nginx) 
- MySQLi extension for PHP
- MySQLnd (native driver; i.e php5-mysqlnd on Ubuntu)
- MCrypt (http://www.php.net//manual/en/book.mcrypt.php)

Most hosts include these prerequisites in PHP stacks, so you probably won't have to install them yourself.

==================

API Documentation: https://github.com/Cydrobolt/polr/wiki

Would like to contribute? Submit a pull request. Found an issue? Create an issue here: https://github.com/Cydrobolt/polr/issues

Polr operates a channel on the freenode IRC network, which can be used for purposes of development or general support. [Webchat](http://webchat.freenode.net/?channels=#polr)

Development
==================

Polr is currently undergoing a massive cleanup/rewrite. Many new features will be implemented, plugins will be supported, and its general code structure will be changed. https://github.com/Cydrobolt/polr/issues/66


==================


####License


    Copyright (C) 2015 Chaoyi Zha

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
