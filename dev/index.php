<?php
@(include('config.php')) or header('Location:setup.php');
include('req.php');
if($ppfrontend==true && !isset($_POST['pw'])) {
echo "<h2>Enter password to proceed:</h2>";
echo "<form action='index.php' method='post'><br><input type='password' name='pw' /><br><input type='submit' value='Log in' /></form>";
die();
}
else if(md5(sha1($_POST['pw']."523422da3a33")+sha1($version.$reldate))==$ppass) {
    echo "<!--logged in-->";
}
else if($ppfrontend!=true) {
    echo "<!--PP disabled-->";
}
else {
    echo "<b>Wrong Password, try again.</b>";
    echo "<form action='index.php' method='post'><br><input type='password' name='pw' /><br><input type='submit' value='Log in' /></form>";
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Polr Status</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="bootstrap.css"/>
            <link rel="stylesheet" href="main.css"/>
        </head>
        <body style="padding-top:60px">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <a class="navbar-brand" href="<?php require_once('req.php');echo 'http://'.$wsa;?>"><?php echo $wsn;?></a>
                <ul class="nav navbar-nav">
                    <li><a href="//github.com/Cydrobolt/polr">Polr Github</a></li>
                </ul>
            </div>
            <div class="container">
                <div class="jumbotron" style="padding-top:80px; background-color: rgba(0,0,0,0);">
                    <h1>Welcome to your Polr Instance</h1>
                    <?php
                    if ($wp==1) {
                        echo "<p style='color: red'>Your Polr instance is improperly configured. Delete config.php and reinstall.</p>";
                    }
                    else {
                        echo "<p style='color: green'>Your Polr instance is properly configured.</p>";
                    }
                    ?>
                    <br>
                    <h2>Shorten a link:</h2>
                    <br><form method="POST" action="createurl.php" role="form"><input type="text" class="form-control" placeholder="URL" id="url" value="http://" name="urlr" /><br> <input type="submit" class="btn btn-info btn-large" value="Shorten it!"/></form><br>
                    <br>If you need help, click <a href="http://webchat.freenode.net/?channels=#polr">here</a> to contact a developer<br>
                <b>Clueless? Read the docs. <a href='https://github.com/Cydrobolt/polr/wiki'>https://github.com/Cydrobolt/polr/wiki</a></b></div>
            </div>
            <div class='footer-pad'>
            <p><?php echo $footer;?></p>
            </div>
        </div>
    </body>
</html>
