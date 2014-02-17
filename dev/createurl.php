<!-- polr -->
<?php require_once("req.php");?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $wsn;?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="bootstrap.css"/>
            <link rel="stylesheet" href="main.css"/>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    </head>
        <body style="padding-top:60px">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <a class="navbar-brand" href="index.php"><?php echo $wsn;?></a>
                <ul class="nav navbar-nav">
                    <li><a href="//github.com/Cydrobolt/polr">Polr Github</a></li>
                </ul>
            </div>
            <div class="container">
                <div class="jumbotron" style="text-align:center; padding-top:80px; background-color: rgba(0,0,0,0);">
				

<?php
if(!filterurl($_POST['urlr'])) {
    echo "You entered an invalid url<br>";
    echo "<a href='index.html'>Back</a>";
    die();
}
$urlr = $_POST['urlr'];
$urlr = $mysqli->real_escape_string($urlr);
//Other URL Shorteners List Array

$isshort = array('polr.cf','bit.ly','is.gd','tiny.cc','adf.ly','ur1.ca','goo.gl','ow.ly','j.mp');

foreach ($isshort as $url_shorteners) {
    if(strstr($urlr, $url_shorteners)) {
    echo "You entered an already shortened URL.<br>";
    echo "<a href='index.html'>Back</a>";
    die();
    }
}
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
        $basevaltd = "http://".$wsa."/".$baseval;
	echo "<div style='text-align:center'>URL:<input type='text' id='i' class='form-control' value=\"$basevaltd\" />";

}
else {
    $query1 = "SELECT baseval FROM redirinfo WHERE rurl='{$urlr}'";
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $baseval = $row['baseval'];
    $basevaltd = "http://".$wsa."/".$baseval;
    echo "<div style='text-align:center'>URL:<input type='text' id='i' class='form-control' value=\"$basevaltd\" />";
}
echo "<a href='index.php'>Back</a>";
echo "</div>";
?>
            <footer>
                <p id="footer-pad"><?php echo $footer;?></p>
            </footer>
        </div>
    </body>
</html>
