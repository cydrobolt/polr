<?php
require_once("req.php");
require_once('header.php');
require_once('polrauth.php');
$polrauth = new polrauth();
$protocol = '://';
$hpi = $_POST['hp'];
$country_code = @$_SERVER["HTTP_CF_IPCOUNTRY"];


if(!strstr($_POST['urlr'], $protocol)) {
    
    $urlr = "http".$protocol.trim($_POST['urlr']); //add http:// if :// not there
}
else {
    $urlr = trim($_POST['urlr']);
}
if(!filterurl($urlr)) {
    echo "You entered an invalid url<br>";
    echo "<a href='index.php'>Back</a>";
    die();
}
if($hpi !== $hp) {
    echo "We have detected that you may be using automated methods to shorten links. <br>We offer a free API, please do not use our shorten page as an API.<br>If you are getting this message, but you are not a bot, please email support@polr.cf <br> Thanks.";
    die();
}

$userinfo = $polrauth->islogged();
if(!is_array($userinfo)) {
    $il = false;
}
else {
    $il = true;
}

$urlr = $mysqli->real_escape_string($urlr);
//Other URL Shorteners List Array

$isshort = array('polr.cf','bit.ly','is.gd','tiny.cc','adf.ly','ur1.ca','goo.gl','ow.ly','j.mp','t.co');

foreach ($isshort as $url_shorteners) {
    if(strstr($urlr, $protocol.$url_shorteners)) {
    echo "You entered an already shortened URL.<br>";
    echo "<a href='index.php'>Back</a>";
    die();
    }
}
$query1 = "SELECT rid FROM redirinfo WHERE rurl='{$urlr}' AND iscustom='no'";
$result = $mysqli->query($query1);
$row = mysqli_fetch_assoc($result);
$existing = $row['rid'];
$decodescript = "<script src='js/durl.js'></script>";
$ip = $mysqli->real_escape_string($ip);
$customurl = $mysqli->real_escape_string($_POST['custom']);
if($customurl == "") {
    $iscustom = "no";
}

//check custom url
$not_allowed_custom = array('.');
if($customurl!="") {
        if(!ctype_alnum($customurl)) {
            echo "<b>Symbols or spaces are not allowed in a customized URL - alphanumeric only. <a href='index.php'>Try again</a></b>";
            die();
        }
        if(strlen($customurl)>20) {
            echo "<b>The maximum length for a custom url is 20 letters. <a href='index.php'>Try again</a></b>";
            die();
        }
}

if(!$existing || $customurl!="") {
	$query1 = "SELECT MAX(rid) AS rid FROM redirinfo;";
	$result = $mysqli->query($query1);
	$row = mysqli_fetch_assoc($result);
	$ridr = $row['rid'];
	$baseval = base_convert($ridr+1,10,36);
        
        if($customurl!="") {
            $baseval = $customurl;
            $iscustom = "yes";
            $query = "SELECT rid FROM redirinfo WHERE baseval='{$customurl}'"; //check if baseval used already
            $result = $mysqli->query($query);
            $row = mysqli_fetch_assoc($result);
            $custom_existing = $row['rid'];
            if($custom_existing) {
                echo "The custom shorturl ending you specified is already in use. <a href='index.php'>Try again</a>";
                die();
            }
        }
        
        $query2 = "INSERT INTO redirinfo (baseval,rurl,ip,user,iscustom,country) VALUES ('{$baseval}','{$urlr}','{$ip}','{$userinfo['username']}','{$iscustom}','{$country_code}');";
        $result2r = $mysqli->query($query2) or showerror();
        $basewsa = base64_encode($wsa);
        $basebv =base64_encode($baseval);
        echo "<input type='hidden' value='$basebv' id='j' /><input type='hidden' value='$basewsa' id='k' />";
        echo $decodescript;
        echo "<div style='text-align:center'>URL: <input type='text' id='i' onselect=\"select_text();\" onclick=\"select_text();\" readonly=\"readonly\" class='form-control' value=\"Please enable Javascript\" />";
        }
else {
    $query1 = "SELECT baseval FROM redirinfo WHERE rurl='{$urlr}' AND iscustom='no'";
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $baseval = $row['baseval'];
    $basebv = base64_encode($baseval);
    $basewsa = base64_encode($wsa);
    echo "<input type='hidden' value='$basebv' id='j' /><input type='hidden' value='$basewsa' id='k' />";
    echo $decodescript;
    echo "URL:<input type='text' id='i' onselect=\"select_text();\" onclick=\"select_text();\" readonly=\"readonly\" class='form-control' value=\"Please enable JavaScript\" />";
    }
echo '<br><a href="index.php" class="btn btn-primary btn-large">Shorten Another Link</a></div>';

require_once('footer.php');
