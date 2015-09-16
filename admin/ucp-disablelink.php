<?php
require_once '../lib-core.php';
require_once '../lib-auth.php';
$polrauth = new polrauth();
$baseval = $mysqli->real_escape_string($_POST['baseval']);
$userinfo = $polrauth->islogged();
if(!is_array($userinfo)) {
    die('401 Unauthorized (not logged in)');
}
$role = $userinfo['role'];
$user = $mysqli->real_escape_string($userinfo['username']);
$date = $mysqli->real_escape_string(time());

if($role!='adm') {
    die('401 Unauthorized (not admin)');
}

$orig = $mysqli->real_escape_string(sqlfetch('redirinfo', 'rurl', 'baseval', $baseval));
$query = "UPDATE redirinfo SET rurl='disabled', etc2='Disabled by {$user} on UNIXDATE {$date}', etc='{$orig}' WHERE baseval='{$baseval}';";
$result = $mysqli->query($query) or die('error');

echo 'success';
die();
