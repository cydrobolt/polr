<?php
//Polr Redirector Core
require_once('req.php');
if (is_string($_GET['u'])) {
    $val = $mysqli->real_escape_string($_GET['u']);
} else {
    echo "Sorry. You didn't enter a string.";
    die();
}

if (strstr($val, "t-")) {
    $query = "SELECT `rurl` FROM `redirinfo-temp` WHERE baseval='{$val}'";
} else {
    $query = "SELECT `rurl`,`lkey` FROM `redirinfo` WHERE baseval='{$val}'";
}
$result = $mysqli->query($query) or showerror();

$row = mysqli_fetch_assoc($result);


if (!$row['rurl']) {
    header("Location: 404.php", true, 302);
}
if (strtolower($row['rurl']) == "disabled") {
    require_once 'header.php';
    echo "<h2>The link you are trying to reach has been disabled by an Administrator</h2><br>"
    . "Sorry for the inconvienience.";
    require_once 'footer.php';
}
$lkey = @$row['lkey'];
if (strlen($lkey)>1) {
	// Key needed? Check for it
	$sent_lkey = isset($_GET[$lkey]);
	if ($sent_lkey) {
		// yup, right key...continue on
	}
	else {
		require_once('header.php');
		echo "Incorrect Key. (http://{$wsa}/abc?keyhere)";
		require_once('footer.php');
		die();
	}
}
header("Location: {$row['rurl']}", true, 301);
$oldclicks = sqlfetch("redirinfo", "clicks", "baseval", $val);
$newclicks = $oldclicks + 1;
sqlrun("UPDATE redirinfo SET clicks='{$newclicks}' WHERE baseval='{$val}'");
