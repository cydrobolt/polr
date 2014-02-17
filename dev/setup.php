<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Polr Setup</title>
            <link rel="stylesheet" href="bootstrap.css"/>
            <link rel="stylesheet" href="main.css"/>
    </head>
        <body style="padding-top:60px">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <a class="navbar-brand" href="//github.com/Cydrobolt/polr">Polr</a>
                <ul class="nav navbar-nav">
                    <li><a href="//github.com/Cydrobolt/polr">Github</a></li>
                </ul>
            </div>
            <div class='container-fluid' style="text-align: center">
                <span><h1>Polr setup</h1></span><br>
        <?php
        @(include('config.php'));
        if (isset($ppass)) {
            if(!isset($_POST['pw'])) {
            echo "<h2>Enter password to proceed:</h2>";
            echo "<form action='setup.php' method='post'><br><input type='password' name='pw' /><br><input type='submit' value='Log in' /></form>";
            die();
            }
            else if($_POST['pw']==$ppass) {
                echo "";
            }
            else {
                echo "Wrong password";
                echo "<h2>Enter password to proceed:</h2>";
                echo "<form action='setup.php' method='post'><br><input type='password' name='pw' /><br><input type='submit' value='Log in' /></form>";
            
                die();
            }
          
        }
        	if (isset($_POST['dbserver'])) {
		$data = '<?php
			$host="'.$_POST['dbserver'].'";'.
			'$user="'.$_POST['dbuser'].'";'.
			'$passwd="'.$_POST['dbpass'].'";'.
			'$db="'.$_POST['dbname'].'";'.
                        '$wsa = "'.$_POST['appurl'].'";'.
                        '$wsn = "'.$_POST['appname'].'";'.
                        '$ppass = "'.$_POST['protpass'].'";'.
                        '$ppfrontend = "'.$_POST['pp'].'";'.
                        '$ip = $_SERVER[\'REMOTE_ADDR\'];
			?>';
		$file = "config.php";

		$handle = fopen($file, 'a');
		if (fwrite($handle, $data) === FALSE) { echo "Can not write to (".$file.")"; }
		echo "Succesfully created config. ";
		fclose($handle);
                require_once('req.php');
                
                //Create Tables
                sqlrun("CREATE TABLE redirinfo"
                        . "("
                        . "rid INT NOT NULL AUTO_INCREMENT,"
                        . "PRIMARY KEY(rid),"
                        . "rurl CHAR(80),"
                        . "baseval CHAR(40),"
                        . "ip CHAR(90)"
                        . ")");
                sqlrun("CREATE TABLE api"
                        . "("
                        . "apikey CHAR(100) NOT NULL,"
                        . "PRIMARY KEY(apikey),"
                        . "email CHAR(80),"
                        . "valid TINYINT(1)"
                        . ")");
                sqlrun("CREATE INDEX index ON redirinfo (rurl, baseval, ip)");
                sqlrun("CREATE INDEX aindex ON api (valid,email)");
                echo "You are now finished Polr Setup. You can now close this window or click <a href='index.php'>here</a> to check the status of the installation. <br><br>If you need help, click <a href=\"http://webchat.freenode.net/?channels=#polr\">here</a><br>"
                . "<br><br><b>Clueless? Read the docs. <a href='https://github.com/Cydrobolt/polr/tree/master/docs'>https://github.com/Cydrobolt/polr/tree/master/docs</a></b>";
	}
	else {
            include('version.php');
		echo "<form name=\"Config Creation\" method=\"post\" action=\"".'setup.php'."\">";
		echo "Database Host: <input type=\"text\" name=\"dbserver\" value=\"localhost\"><br>";
		echo "Database User: <input type=\"text\" name=\"dbuser\" value=\"root\"><br>";
		echo "Database Pass: <input type=\"password\" name=\"dbpass\" value=\"password\"><br>";
		echo "Database Name: <input type=\"text\" name=\"dbname\" value=\"polr\"><br>";
                echo "Application Name: <input type=\"text\" name=\"appname\" value=\"polr\"><br>";
                echo "Application URL (path to Polr, no http:// or www.) : <input type=\"text\" name=\"appurl\" value=\"yoursite.com\"><br>";
                echo "App Access Password: <input type=\"text\" name=\"protpass\" value=\"password123\"><br>";
                if(isset($_POST['pw'])) {
                    echo "<input type='hidden' value='{$_POST['pw']}' name='pw' />";
                }
                echo "<input type=\"checkbox\" name=\"pp\" value=\"Password-Protect\">Password Protect the Frontend (shortener)<br><br>";
		echo "<input type=\"submit\" value=\"Create/Update config\"><input type=\"reset\" value=\"Clear Fields\">";
		echo "</form>";
                echo "<br><br></div><div class='container'><b>Make sure the databse you specify is already created. The database user needs to"
                . "already have been created. If you enter the wrong information, go in the installation "
                        . "directory and delete config.php.<br>Please grant the mysql user all privileges"
                        . "during the setup, and then restrict the user to only CREATE, UPDATE, INSERT, DELETE, and SELECT.</b>";
                echo "<br><br>Polr is <a href='http://en.wikipedia.org/wiki/Open-source_software'>Open-Source software</a> licensed under the <a href='https://www.gnu.org/copyleft/gpl.html'>GPL</a>. By continuing to use Polr, you agree to the terms of the GPL.";
                echo "<footer>Polr Version $version released $reldate - <a href='//github.com/cydrobolt/polr'>Github</a></footer>";
	}
        ?>
            </div>
    </body>
</html>
