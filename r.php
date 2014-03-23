<?php
//POLR Redirector
require_once('req.php');
if(is_string($_GET['u'])) {
    $val = $mysqli->real_escape_string($_GET['u']);
}
else {
    echo "Sorry. You didn't enter a string.";
    die();
}
$query = "SELECT rurl FROM redirinfo WHERE baseval='{$val}'";
$result = $mysqli->query($query) or showerror();

$row = mysqli_fetch_assoc($result);

if(!$row['rurl']) {
    header("Location: 404.php",true,301);
    }
else {
    header("Location: {$row['rurl']}",true,301);
	$oldclicks = sqlfetch("redirinfo","clicks","baseval",$val);
	$newclicks = $oldclicks+1;
	sqlrun("UPDATE redirinfo SET clicks='{$newclicks}' WHERE baseval='{$val}'");
}


