<?php
require_once('lib-password.php'); //password hashing lib - crpypt forward compat
require_once('lib-core.php');
require_once('lib-auth.php');
$polrauth = new polrauth();
$authcreds['username'] = $mysqli->real_escape_string($_POST['username']);
$authcreds['password'] = $mysqli->real_escape_string($_POST['password']);
if(strstr($authcreds['username'], ' ')) {
    $authcreds['username'] = trim($authcreds['username']);
}

$authed = $polrauth->processlogin($authcreds['username'],$authcreds['password']);

if($authed==true) {
    $_SESSION['li'] = sha1('li');
    $_SESSION['username'] = $authcreds['username'];
    $_SESSION['role'] = $polrauth->getrole($authcreds['username']);

    header('Location:index.php');
}
else {
    require_once('layout-headerlg.php');
    echo '<h2>Incorrect password or username (or account not activated). Try again</h2><br />';
    if ($fpass == true) {
        echo '<a href="forgotpass.php">Forgot Password?</a><br />';
    }
    require_once('layout-footerlg.php');
    die();
}
