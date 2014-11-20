<?php
$req_noredirect = true;
require_once('req.php');
require_once('header.php');

echo "<h2>Error:</h2><br />";
echo "<ul>";
$err = 0;
if (!$mysqli) {
    $err++;
    echo "<li>Could not connect to database. Please contact the administrator.";
}
if ($insecure == TRUE && $_SESSION['role'] == 'adm') {
    $err++;
    echo "<li>You are seeing the following message because you are an administrator. Your MySQL database is potentially unsafe. P
          lease make sure Polr's database receives an UTF8 encoding. </li>";
}
if ($err<1) {
    echo "<li>No errors to report.</li>";
}
  
echo "</ul>";

require_once('footer.php');
