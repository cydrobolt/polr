<!DOCTYPE html>
<!--
# Copyright (C) 2013-2014 Chaoyi Zha
# Polr is an open-source project licensed under the GPL.
# The above copyright notice and the following license are applicable to
# the entire project, unless explicitly defined otherwise.
# http://github.com/cydrobolt/polr
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or (at
# your option) any later version.
# See http://www.gnu.org/copyleft/gpl.html  for the full text of the
# license.
#
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Polr Setup</title>
        <link rel="stylesheet" href="install-bootstrap.css"/>
    </head>
    <body style="padding-top:60px">
        <div class="navbar navbar-default navbar-fixed-top">
            <a class="navbar-brand" href="index.php">Polr</a>
        </div>
        <div class='container-fluid push pushtop' style="text-align: left">
            <span><h1 style="text-align:center">Polr Setup</h1></span><br>
            <?php
            @(include('config.php'));
            include ('version.php');
            require_once 'password.php';
            date_default_timezone_set('UTC');
            $mysqlnd = function_exists('mysqli_fetch_all');

			if (!$mysqlnd) {
				echo "<br /><br /><p>Error: You don't seem to have the <code>MySQL native driver. </code>
				<br />You should install it and restart your server in order to use Polr properly. <br />
				On Ubuntu-based distros: <code>sudo apt-get install php5-mysqlnd</code><br />
				On Fedora-based distros: <code>sudo yum install php-mysqlnd</code>, or if you get errors, <code>sudo yum remove php-mysql && yum install php-mysqlnd</code><br />
				For most Windows computers, the native driver should come by default for PHP >= 5.4.
				<br />For more information, click <a href='http://php.net/manual/en/mysqlnd.install.php'>here</a></p>";
				die();
			}


            function rstr($length = 34) {
                return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
            }

            if (isset($ppass)) {
                if (!isset($_POST['pw'])) {
                    require_once 'header.php';
                    echo "<h2>Enter setup password to proceed:</h2>";
                    echo "<form action='setup.php' method='post'><br><input class='form-control' type='password' name='pw' /><br><input type='submit' class='form-control' value='Log in' /></form>";
                    require_once 'footer.php';
                    die();
                } else if ($pwf = password_verify($_POST['pw'], $ppass)) {
                    echo "";
                } else {
                    require_once 'header.php';
                    echo "Wrong password<br>";
                    echo "<h2>Enter setup password to proceed:</h2>";
                    echo "<form action='setup.php' method='post'><br><input type='password' class='form-control' name='pw' /><br><input type='submit' class='form-control' value='Log in' /></form>";
                    require_once 'footer.php';
                    die();
                }
            }
            if (isset($_POST['dbserver'])) {
                $rstr = rstr(50);

                function hashpass($pass, $salt = "") {
                    if (!$salt) {
                        $salt = rstr(60);
                    }
                    $opts = array(
                        'cost' => 10,
                        'salt' => $salt
                    );
                    $hashed = password_hash($pass, PASSWORD_BCRYPT, $opts);
                    return $hashed;
                }

                $nowdate = date('F d Y');
                $data = '<?php
			            $host="' . $_POST['dbserver'] . '";' .
                        '$user="' . $_POST['dbuser'] . '";' .
                        '$passwd="' . $_POST['dbpass'] . '";' .
                        '$db="' . $_POST['dbname'] . '";' .
                        '$wsa = "' . $_POST['appurl'] . '";' .
                        '$wsn = "' . $_POST['appname'] . '";' .
                        '$wsb = "' . $nowdate . '";' .
                        '$ppass = \'' . hashpass($_POST['protpass']) . '\';' .
                        '$ip = $_SERVER[\'REMOTE_ADDR\'];'
                        . '$hp = "' . sha1(rstr(30)) . "\";"
                        . '$regtype = "' . $_POST['reg'] . "\";"
                        . '$path = "' . $_POST['path'] . "\";"
                        . '$fpass = ' . $_POST['fpass'] . ";"
                        . '$theme = "' . $_POST['t'] . "\";"
                        . '$ip = ' . $_POST['ipfetch'] . ";"
                        . '$unstr = "' . $rstr . '";';
			    if (strlen($_POST['smtp-servers'])>1) {
                    $smtpSection = '
                        $smtpCfg = array(
                            "servers"  => "'.$_POST['smtp-servers'].'",
                            "from" => "'.$_POST['smtp-from'].'",
                            "username" => "'.$_POST['smtp-username'].'",
                            "password" => "'.$_POST['smtp-password'].'",
                        );
                    ';
                    $data .= $smtpSection;
                }
                $data .= '?>';
                $file = "config.php";

                $handle = fopen($file, 'a');
                if (fwrite($handle, $data) === FALSE) {
                    echo "Can not write to (" . $file . ")";
                }
                echo "Succesfully created config. ";
                fclose($handle);
                require_once('req.php');
                $path = $_POST['path'];
                if (strlen($path) > 2) {
                    $data = "<IfModule mod_rewrite.c>
                            RewriteEngine On
                            RewriteRule ^api$ /api.php [L]
                            RewriteRule ^api/$ /api.php [L]
                            RewriteCond %{REQUEST_FILENAME} !-f
                            RewriteCond %{REQUEST_FILENAME} !-d
                            RewriteRule ^([a-zA-Z0-9]+)\?([a-zA-Z0-9]+)$ /r.php?u=$1&lkey=$2 [L,QSA]
                            RewriteRule ^([a-zA-Z0-9]+)/?$ /r.php?u=$1 [L,QSA]
                            RewriteRule ^t-([a-zA-Z0-9]+)/?$ /r.php?u=t-$1 [L,QSA]
                            RewriteRule ^/?\+([a-zA-Z0-9]+)$ /stats.php?bv=$1 [L,QSA]
                            </IfModule>
                            ";
                    $handle = fopen('.htaccess', 'w');
                    if (fwrite($handle, $data) === FALSE) {
                        echo "Can not write to (" . $file . ")";
                    }
                    echo "Succesfully created htaccess (custom path) <br />. ";
                    fclose($handle);
                }

                sqlrun('
                CREATE TABLE `api` (
                  `valid` tinyint(1) NOT NULL,
                  `email` varchar(50) NOT NULL,
                  `apikey` varchar(70) NOT NULL,
                  `quota` int(11) NOT NULL,
                  PRIMARY KEY (`apikey`),
                  UNIQUE KEY `email` (`email`),
                  KEY `email_2` (`email`),
                  KEY `valid` (`valid`),
                  KEY `aindex` (`valid`,`email`)
                );');

                sqlrun('
                CREATE TABLE `auth` (
                    `username` varchar(50) NOT NULL,
                    `password` text NOT NULL,
                    `email` varchar(65) NOT NULL,
                    `rkey` varchar(65) NOT NULL,
                    `role` varchar(37) NOT NULL,
                    `valid` tinyint(1) NOT NULL DEFAULT "0",
                    `uid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `theme` varchar(65) NOT NULL,
                    `ip` tinytext NOT NULL,
                    KEY `valid` (`valid`),
                    KEY `email3` (`email`),
                    KEY `username2` (`username`)
                );');

                sqlrun('
               CREATE TABLE `redirinfo` (
                  `rurl` varchar(80) NOT NULL,
                  `rid` smallint(200) NOT NULL AUTO_INCREMENT,
                  `baseval` varchar(30) NOT NULL,
                  `ip` varchar(90) NOT NULL,
                  `iscustom` varchar(4) NOT NULL,
                  `user` tinytext NOT NULL,
                  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `country` varchar(10) NOT NULL,
                  `lkey` tinytext NOT NULL,
                  `clicks` int(11) NOT NULL,
                  `pw` int(120) NOT NULL,
                  `etc` text,
                  `etc2` text,
                  PRIMARY KEY (`rid`),
                  KEY `rurl` (`rurl`),
                  KEY `baseval` (`baseval`),
                  KEY `baseval_2` (`baseval`),
                  KEY `rurl_2` (`rurl`),
                  KEY `ip` (`ip`),
                  KEY `iscustom` (`iscustom`),
                  KEY `rurl_3` (`rurl`,`rid`,`baseval`,`ip`,`iscustom`)
                );');
                sqlrun('
               CREATE TABLE `redirinfo-temp` (
                  `rurl` varchar(80) NOT NULL,
                  `rid` smallint(200) NOT NULL AUTO_INCREMENT,
                  `baseval` varchar(30) NOT NULL,
                  `ip` varchar(90) NOT NULL,
                  `iscustom` varchar(4) NOT NULL,
                  `user` tinytext NOT NULL,
                  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `country` tinytext NOT NULL,
                  `theme` varchar(65) NOT NULL,
                  `clicks` int(11) NOT NULL,
                  `pw` int(120) NOT NULL,
                  `etc` text,
                  `etc2` text,
                  PRIMARY KEY (`rid`),
                  KEY `rurl` (`rurl`),
                  KEY `baseval` (`baseval`),
                  KEY `baseval_2` (`baseval`),
                  KEY `rurl_2` (`rurl`),
                  KEY `ip` (`ip`),
                  KEY `iscustom` (`iscustom`),
                  KEY `rurl_3` (`rurl`,`rid`,`baseval`,`ip`,`iscustom`)
                );');
                $acctpass = hashpass($_POST['acctpass']);
                $nr = sha1(rstr(50));
                sqlrun("INSERT INTO auth (username,email,password,rkey,valid,role) VALUES ('{$_POST['acct']}','{$_POST['acctemail']}','{$acctpass}','{$nr}','1','adm') ");
                echo "You are now finished Polr Setup. You can now close this window, and login to your account <a href='index.php'>here</a> (login form @ top right). <br><br>If you need help, click <a href=\"http://webchat.freenode.net/?channels=#polr\">here</a><br>"
                . "<br><br><b>Clueless? Read the docs. <a href='https://github.com/Cydrobolt/polr/blob/master/README.md'>https://github.com/Cydrobolt/polr/blob/master/README.md</a></b>";
            } else {
                include('version.php');
                echo "<form name=\"Config Creation\" style='margin:0 auto; width: 650px' method=\"post\" action=\"" . 'setup.php' . "\">";

                // DB Config
                echo "<b style=\"text-align:center\">Database Configuration</b><br />";
                echo "Database Host: <input type=\"text\" class='form-control' style='width:650px' name=\"dbserver\" value=\"localhost\"><br>";
                echo "Database User: <input type=\"text\" class='form-control' style='width:650px' name=\"dbuser\" value=\"root\"><br>";
                echo "Database Pass: <input type=\"password\" class='form-control' style='width:650px' name=\"dbpass\" value=\"password\"><br>";
                echo "Database Name: <input type=\"text\" class='form-control' style='width:650px' name=\"dbname\" value=\"polr\"><br>";

                // App Config
                echo "<br /><b style=\"text-align:center\">Application Settings</b><br />";
                echo "Application Name: <input type=\"text\" class='form-control' style='width:650px' name=\"appname\" value=\"polr\"><br>";
                echo "Application URL (path to Polr, no http:// or www.) : <input type=\"text\" style='width:650px' class='form-control' name=\"appurl\" value=\"yoursite.com\"><br>";
                echo "Fetch ip through variable: <input type=\"text\" class='form-control' style='width:650px' name=\"ipfetch\" value=\"\$_SERVER['REMOTE_ADDR']\"><br>";

                // Security/Account Config
                echo "<br /><b style=\"text-align:center\">Admin Account Settings</b><br />";
                echo "Setup Access Password: <input type=\"text\" class='form-control' style='width:650px' name=\"protpass\" value=\"password123\"><br>";
                echo "Admin Account: <input type=\"text\" class='form-control' style='width:650px' style='width:650px' name=\"acct\" value=\"polr\"><br>";
                echo "Admin Email: <input type=\"text\" class='form-control' style='width:650px' style='width:650px' name=\"acctemail\" value=\"polr@admin.tld\"><br>";
                echo "Admin Password: <input type=\"password\" style='width:650px' class='form-control' name=\"acctpass\" value=\"polr\"><br>";

                // SMTP Config
                echo "<br /><b style=\"text-align:center\">SMTP Settings</b><p>(leave blank if you are not using email verification/password recovery)</p>";
                echo "SMTP Servers (semicolon separated): <input type=\"text\" class='form-control' style='width:650px' name=\"smtp-servers\" placeholder=\"smtp.gmail.com\"><br>";
                echo "SMTP Username: <input type=\"text\" class='form-control' style='width:650px' name=\"smtp-username\" placeholder=\"example@gmail.com\"><br>";
                echo "SMTP Password: <input type=\"password\" class='form-control' style='width:650px' name=\"smtp-password\" placeholder=\"password\"><br>";
                echo "SMTP From: <input type=\"text\" class='form-control' style='width:650px' name=\"smtp-from\" placeholder=\"example@gmail.com\"><br>";


                echo "<br /><b style=\"text-align:center\">Other Settings</b><br />Registration: <select name='reg' style='width:650px' class='form-control'>"
                . "<option value='none'>No registration</option>"
                . "<option value='email'>Email verification required</option>"
                . "<option value='free'>No email verification required</option>"
                . "<option value='regulated'>New registrations must be approved (do not choose, future option)</option>"
                . "</select><br /><br />";
                echo "Password Recovery: <select name='fpass' style='width:650px' class='form-control'>"
                . "<option value='false'>No (default)</option>"
                . "<option value='true'>Yes (could cause problems unless sgmail.php/email is properly set up)</option>"
                . "</select><br /><br />";
                echo "Path relative to root (leave blank if /, if http://site.com/polr, then write /polr/): <input type=\"text\" class='form-control' style='width:650px' name=\"path\" value=\"/polr/\"><br>";
                echo "Theme (choose wisely, click <a href='https://github.com/Cydrobolt/polr/wiki/Themes-Screenshots'>here</a> for screenshots: <select name='t' style='width:650px' class='form-control'>"
                . "<option value=''>Modern (default)</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/cyborg/bootstrap.min.css'>Midnight Black</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/amelia/bootstrap.min.css'>Cheery</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/united/bootstrap.min.css'>Orange</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/simplex/bootstrap.min.css'>Crisp White</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/darkly/bootstrap.min.css'>Cloudy Night</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/cerulean/bootstrap.min.css'>Calm Skies</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/paper/bootstrap.min.css'>Android Material Design</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/superhero/bootstrap.min.css'>Blue Metro</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/sandstone/bootstrap.min.css'>Sandstone</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/cyborg/bootstrap.min.css'>Jet Black</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/lumen/bootstrap.min.css'>Newspaper</option>"

                . "</select><br /><br />";

                if (isset($_POST['pw'])) {
                    echo "<input type='hidden' value='{$_POST['pw']}' name='pw' />";
                }
                echo "<div id='buttons' style='text-align:center'>";
                if (isset($ppass)) {
                    echo "<input type=\"submit\" class=\"btn btn-success\" style='width:150px' value=\"Update\">";
                } else {
                    echo "<input type=\"submit\" class=\"btn btn-success\" style='width:150px' value=\"Install\">";
                }
                echo "&nbsp; &nbsp; &nbsp;";
                echo "<input type=\"reset\" value=\"Clear Fields\" class=\"btn btn-warning\" style='width:150px'>";
                echo "</div>";
                echo "</form>";
                echo "<br><br></div><div class='container' style='text-align:center'>"
                . "<p><b>Please read the README.md file located in the root Polr directory. It contains essential and indispensable troubleshooting, installation, and support materials. <b/></p>";
                echo "<br><br>Polr is <a href='http://en.wikipedia.org/wiki/Open-source_software'>Open-Source software</a> licensed under the <a href='//www.gnu.org/copyleft/gpl.html'>GPL License</a>. By continuing to use Polr, you agree to the terms of the MIT License.";
                echo "<div class=''>Polr Version $version released $reldate - <a href='//github.com/cydrobolt/polr'>Github</a></div></div><br><span style='padding-left:4%'>&copy; Copyright $relyear Chaoyi Zha & <a href='https://github.com/Cydrobolt/polr/graphs/contributors'>Other Polr Contributors</a></span>";
            }
            ?>
        </div>
    </body>
</html>
