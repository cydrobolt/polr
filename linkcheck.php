<?php

require_once('req.php');
require_once('ajaxhandler.php');

$ajaxhandler = new ajaxhandler();

$link = $mysqli->real_escape_string($_POST['link']);
$checked = $ajaxhandler->linkcheck($link,$mysqli);
if ($checked==1) {
    echo "1";
    die();
}
elseif ($checked==0) {
    echo '0';
    die();
}
else {
    echo '2';
    die();
}
