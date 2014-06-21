<?php
require_once 'req.php';
require_once 'polrauth.php';
$polrauth = new polrauth();
$baseval = $mysqli->real_escape_string($_POST['baseval']);
$userinfo = $polrauth->islogged();
if(!is_array($userinfo)) {
    //not logged in
    die('401 Unauthorized (not logged in)');
}
$role = $userinfo['role'];
$user = $mysqli->real_escape_string($userinfo['username']);
$date = $mysqli->real_escape_string(time());

if($role!='adm') {
    die('401 Unauthorized (not admin)');
}

$curr = $mysqli->real_escape_string(sqlfetch('redirinfo', 'rurl', 'baseval', $baseval));
if($curr!='disabled') {
    die('already enabled, error');
}
//if all works out
$orig['url'] = $mysqli->real_escape_string(sqlfetch('redirinfo', 'etc', 'baseval', $baseval));

$query = "UPDATE redirinfo SET rurl='{$orig['url']}', etc2='Reenabled by {$user} on UNIXDATE {$date}', etc='' WHERE baseval='{$baseval}';";
$result = $mysqli->query($query) or die('error');

echo 'success';
die(); //all works out :)