<!DOCTYPE html>
<!--
 * Polr setup page.
 * The Polr Project - https://github.com/Cydrobolt/polr
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Polr Setup</title>
        <link rel="stylesheet" href="bootstrap.css"/>
        <link rel="stylesheet" href="main.css"/>
    </head>
    <body style="padding-top:60px">
        <div class="navbar navbar-default navbar-fixed-top">
            <a class="navbar-brand" href="//github.com/Cydrobolt/polr">Polr</a>
        </div>
        <div class='container-fluid push pushtop' style="text-align: left">
            <span><h1>Polr Setup</h1></span><br>
            <?php
            @(include('config.php'));
            include ('version.php');
            require_once 'password.php';

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
                    $opts = [
                        'cost' => 10,
                        'salt' => $salt
                    ];
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
                        . '$theme = "' . $_POST['t'] . "\";"
                        . '$ip = ' . $_POST['ipfetch'] . ";" .
                        '$unstr = "' . $rstr . '";
			?>';
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
                            RewriteBase $path
                            RewriteRule ^api$ api.php [L]
                            RewriteRule ^api/$ api.php [L]

                            RewriteCond %{REQUEST_FILENAME} !-f
                            RewriteCond %{REQUEST_FILENAME} !-d
                            RewriteRule ^([a-zA-Z0-9]+)/?$ r.php?u=$1 [L,QSA]
                            RewriteRule ^t-([a-zA-Z0-9]+)/?$ r.php?u=t-$1 [L,QSA]
                            RewriteRule ^/?\+([a-zA-Z0-9]+)$ stats.php?bv=$1 [L,QSA]
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
                sqlrun("INSERT INTO auth (username,email,password,rkey,valid,role) VALUES ('{$_POST['acct']}','polr@admin.none','{$acctpass}','{$nr}','1','adm') ");
                echo "You are now finished Polr Setup. You can now close this window, and login to your account <a href='index.php'>here</a> (login form @ top right). <br><br>If you need help, click <a href=\"http://webchat.freenode.net/?channels=#polr\">here</a><br>"
                . "<br><br><b>Clueless? Read the docs. <a href='https://github.com/Cydrobolt/polr/wiki'>https://github.com/Cydrobolt/polr/wiki</a></b>";
            } else {
                include('version.php');
                echo "<form name=\"Config Creation\" method=\"post\" action=\"" . 'setup.php' . "\">";
                echo "Database Host: <input type=\"text\" class='form-control' name=\"dbserver\" value=\"localhost\"><br>";
                echo "Database User: <input type=\"text\" class='form-control' name=\"dbuser\" value=\"root\"><br>";
                echo "Database Pass: <input type=\"password\" class='form-control' name=\"dbpass\" value=\"password\"><br>";
                echo "Database Name: <input type=\"text\" class='form-control' name=\"dbname\" value=\"polr\"><br>";
                echo "Application Name: <input type=\"text\" class='form-control' name=\"appname\" value=\"polr\"><br>";
                echo "Application URL (path to Polr, no http:// or www.) : <input type=\"text\" class='form-control' name=\"appurl\" value=\"yoursite.com\"><br>";
                echo "Setup Access Password: <input type=\"text\" class='form-control' name=\"protpass\" value=\"password123\"><br>";
                echo "Admin Account: <input type=\"text\" class='form-control' name=\"acct\" value=\"polr\"><br>";
                echo "Admin Password: <input type=\"password\" class='form-control' name=\"acctpass\" value=\"polr\"><br>";
                echo "Fetch ip through variable: <input type=\"text\" class='form-control' name=\"ipfetch\" value=\"\$_SERVER['REMOTE_ADDR']\"><br>";
                echo "Registration: <select name='reg' class='form-control'>"
                . "<option value='none'>No registration</option>"
                . "<option value='email'>Email verification required</option>"
                . "<option value='free'>No email verification required</option>"
                . "<option value='regulated'>New registrations must be approved (do not choose, future option)</option>"
                . "</select><br /><br />";
                echo "Path relative to root (leave blank if /, if http://site.com/polr, then write /polr/): <input type=\"text\" class='form-control' name=\"path\" value=\"/polr/\"><br>";
                echo "Theme (choose wisely, click <a href='https://github.com/Cydrobolt/polr/wiki/Themes-Screenshots'>here</a> for screenshots: <select name='theme' class='form-control'>"
                . "<option value='bootstrap.css'>Modern (default)</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/cyborg/bootstrap.min.css'>Midnight Black</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/amelia/bootstrap.min.css'>Cheery</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/united/bootstrap.min.css'>Orange</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/simplex/bootstrap.min.css'>Crisp White</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/darkly/bootstrap.min.css'>Cloudy Night</option>"
                . "<option value='//maxcdn.bootstrapcdn.com/bootswatch/3.1.1/cerulean/bootstrap.min.css'>Calm Skies</option>"
                . "</select><br /><br />";

                if (isset($_POST['pw'])) {
                    echo "<input type='hidden' value='{$_POST['pw']}' name='pw' />";
                }
                echo "<input type=\"submit\" value=\"Create/Update config\"><input type=\"reset\" value=\"Clear Fields\">";
                echo "</form>";
                echo "<br><br></div><div class='container'>"
                . "Requirements:<br><br>"
                . "<dl>"
                . "<dt>MySQL >= 5.4 (5.5 recommended)"
                . "<dt>PHP >= 5.4 (using 5.5 or higher? remove password.php)"
                . "<dt>MCrypt Module for PHP (if you do not have this, download the alternative version)"
                . "</dl><br>"
                . "<b>Make sure the databse you specify is already created. The database user also needs to"
                . "be pre-created. If you enter the wrong information, go in the installation "
                . "directory and delete config.php.<br>Please grant the mysql user all privileges"
                . "during the setup, and then restrict the user to only CREATE, UPDATE, INSERT, DELETE, and SELECT.</b>";
                echo "<br><br>Polr is <a href='http://en.wikipedia.org/wiki/Open-source_software'>Open-Source software</a> licensed under the <a href='//opensource.org/licenses/MIT'>MIT License</a>. By continuing to use Polr, you agree to the terms of the MIT License.";
                echo "<div class=''>Polr Version $version released $reldate - <a href='//github.com/cydrobolt/polr'>Github</a><br>&copy; Copyright $relyear Chaoyi Zha & <a href='https://github.com/Cydrobolt/polr/graphs/contributors'>Other Polr Contributors</a></div></div>";
            }
            ?>
        </div>
    </body>
</html>