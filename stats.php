<?php
require_once 'layout-headerlg.php';
if (is_string($_GET['bv']) && ctype_alnum($_GET['bv'])) {
    $bv = $mysqli->real_escape_string($_GET['bv']);
} else {
    echo "<h2>You attempted to show stats for a link that does not exist, or you are formatting your link wrong.</h2>";
    require_once 'layout-footerlg.php';
    die();
}
$query = "SELECT `clicks`,`country`,`rurl`,`lkey` FROM redirinfo WHERE baseval='{$bv}';";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);

if(isset($row['lkey']) == true && strlen($row['lkey']) > 0) {
    echo "<h3>Cannot show stats for a secret URL</h3>";
    die();
}
if(!isset($row)) {
    echo "404 Not Found";
    require_once 'layout-footerlg.php';
    die();
}

if(!isset($row['user']) || strlen($row['user']) < 1) {
    $row['user'] = '<i>Anonymous</i>';
}
if(!isset($row['country']) || strlen($row['country']) < 1) {
    $row['country'] = '<i>Unknown</i>';
}


echo "<h2 style='display:inline'>Link Stats for </h2><h2 style='color:green'>$wsa/".$bv.'</h2><p class="text-muted">'.$row['rurl'].'</p><br>';
echo "<div class='col-md-4'><h2>Clicks</h2><span style='color:blue'>{$row['clicks']}</span></div>";
echo "<div class='col-md-4'><h2>Created by</h2><span style='color:red'>{$row['user']}</span></div>";
echo "<div class='col-md-4'><h2>Country</h2><span style='color:grey'>{$row['country']}</span></div>";

require_once 'layout-footerlg.php';
