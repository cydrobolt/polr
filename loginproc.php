<?php
require_once('password.php'); //password hashing lib - crpypt forward compat
require_once('req.php');
require_once('polrauth.php');
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
    require_once('header.php');
    echo '<h2>Incorrect password or username (or account not activated). Try again</h2><br>';
    require_once('footer.php');
    die();
}