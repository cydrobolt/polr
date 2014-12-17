<?php
require_once 'header.php';
if (is_string($_GET['bv']) && ctype_alnum($_GET['bv'])) {
    $bv = $mysqli->real_escape_string($_GET['bv']);
} else {
    echo "<h2>You attempted to show stats for a link that does not exist, or you are formatting your link wrong.</h2>";
    require_once 'footer.php';
    die();
}
$query = "SELECT `clicks`,`country`,`rurl` FROM redirinfo WHERE baseval='{$bv}';";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);
if(!$row) {
    echo "404 Not Found";
    require_once 'footer.php';die();
}

if(!$row['user']) {
    $row['user'] = '<i>Anonymous</i>';
}
if(!$row['country']) {
    $row['country'] = '<i>Unknown</i>';
}


echo "<h2 style='display:inline'>Link Stats for </h2><h2 style='color:green'>$wsa/".$bv.'</h2><p class="text-muted">'.$row['rurl'].'</p><br>';
echo "<div class='col-md-4'><h2>Clicks</h2><span style='color:blue'>{$row['clicks']}</span></div>";
echo "<div class='col-md-4'><h2>Created by</h2><span style='color:red'>{$row['user']}</span></div>";
echo "<div class='col-md-4'><h2>Country</h2><span style='color:grey'>{$row['country']}</span></div>";

require_once 'footer.php';
