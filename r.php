<?php
/*
	Redirect to target URL.
*/

require_once('lib-core.php');
if (is_string($_GET['u'])) {
    $val = $mysqli->real_escape_string($_GET['u']);
} else {
    echo "Sorry. You didn't enter a valid ending.";
    die();
}

if (strstr($val, "t-")) {
    $query = "SELECT `rurl` FROM `redirinfo-temp` WHERE baseval='{$val}'";
} else {
    $query = "SELECT `rurl`,`lkey` FROM `redirinfo` WHERE baseval='{$val}'";
}
$result = $mysqli->query($query) or showerror();

$row = mysqli_fetch_assoc($result);

if (!isset($row['rurl']) || strlen($row['rurl']) < 1) {
    header("Location: 404.php", true, 302);
    die();
}
if (strtolower($row['rurl']) == "disabled") {
    require_once 'layout-headerlg.php';
    echo "<h2>The link you are trying to reach has been disabled.</h2><br>"
    . "Sorry for the inconvienience.";
    require_once 'layout-footerlg.php';
    die();
}
$lkey = $row['lkey'];
if (strlen($lkey) > 1) {
	// check for key
	$sent_lkey = $_GET[$lkey];
	if ($sent_lkey == $lkey) {
		// correct key
	}
	else {
		require_once('layout-headerlg.php');
		echo "Incorrect Key. (http://{$wsa}/abc?keyhere)";
		require_once('layout-footerlg.php');
		die();
	}
}
header("Location: {$row['rurl']}", true, 301);
$oldclicks = sqlfetch("redirinfo", "clicks", "baseval", $val);
$newclicks = $oldclicks + 1;
sqlrun("UPDATE redirinfo SET clicks='{$newclicks}' WHERE baseval='{$val}'");
