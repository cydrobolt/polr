<!-- polr -->

<!DOCTYPE html>
<html>
    <head>
        <title>Polr</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="bootstrap.css"/>
            <link rel="stylesheet" href="main.css"/>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    </head>
        <body style="padding-top:60px">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <a class="navbar-brand" href="index.html">Polr</a>
                <ul class="nav navbar-nav">
                    <li><a href="//github.com/Cydrobolt/polr">Github</a></li>
                    <li><a href="about.html">About</a></li>
                </ul>
            </div>
            <div class="container">
                <div class="jumbotron" style="text-align:center; padding-top:80px; background-color: rgba(0,0,0,0);">
				

<?php
require_once("req.php");
if(!filterurl($_POST['urlr'])) {
    echo "You entered an invalid url<br>";
    echo "<a href='index.html'>Back</a>";
    die();
}
$urlr = $_POST['urlr'];
$urlr = $mysqli->real_escape_string($urlr);
$query1 = "SELECT rid FROM redirinfo WHERE rurl='{$urlr}'";
$result = $mysqli->query($query1);
$row = mysqli_fetch_assoc($result);
$existing = $row['rid'];
$decodescript = "<script src='durl.js'></script>";
$ip = $mysqli->real_escape_string($ip);

if(!$existing) {
	$query1 = "SELECT MAX(rid) AS rid FROM redirinfo;";
	$result = $mysqli->query($query1);
	$row = mysqli_fetch_assoc($result);
	$ridr = $row['rid'];
	$baseval = base_convert($ridr+1,10,36);
        $query2 = "INSERT INTO redirinfo (baseval,rurl,ip) VALUES ('{$baseval}','{$urlr}','{$ip}');";
        $result2r = $mysqli->query($query2) or showerror();
        $basewsa = base64_encode($wsa);
        $basebv =base64_encode($baseval);
        echo "<input type='hidden' value='$basebv' id='j' /><input type='hidden' value='$basewsa' id='k' />";
        echo $decodescript;
        echo "<div style='text-align:center'>URL: <input type='text' id='i' class='form-control' value=\"Please enable Javascript\" />";
        }
else {
    $query1 = "SELECT baseval FROM redirinfo WHERE rurl='{$urlr}'";
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $baseval = $row['baseval'];
    $basebv = base64_encode($baseval);
    $basewsa = base64_encode($wsa);
    echo "<input type='hidden' value='$basebv' id='j' /><input type='hidden' value='$basewsa' id='k' />";
    echo $decodescript;
    echo "<div style='text-align:center'>URL:<input type='text' id='i' class='form-control' value=\"Please enable JavaScript\" />";
    }
echo "</div>";
?>
            <footer>
                <p id="footer-pad">&copy; Copyright 2013 Polr - Special Thanks to <a href="http://mywot.com">WOT</a></p>
            </footer>
        </div>
    </body>
</html>
