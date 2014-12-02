<?php
require_once("req.php");
require_once('header.php');
require_once('polrauth.php');
$polrauth = new polrauth();
$protocol = '://';
$hpi = $_POST['hp'];
$ps = $_POST['options'];

$country_code = @$_SERVER["HTTP_CF_IPCOUNTRY"];
if ($li_shorten_only == true) {
    if (!isset($_SESSION['username']) {
        require_once('header.php');
        echo "<h2>Only logged in users may shorten links. Did you mean to <a href='login.php'>log in</a>?</h2>";
        require_once('footer.php');
        die();
    }
}


function bve($bv) {
    global $mysqli;
    $query1 = "SELECT `rid` FROM `redirinfo` WHERE baseval='{$bv}'"; // Check if exists natura
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $existing = $row['rid'];
    if ($existing != NULL ) {
        return true;
    }
    else {
        return false;
    }
}

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
function rStr($length = 4) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
if($ps == "s") {
	// if secret url
	$rstr = rStr(4);
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

$isshort = array('polr.me', 'polr.cf','bit.ly','is.gd','tiny.cc','adf.ly','ur1.ca','goo.gl','ow.ly','j.mp','t.co');

foreach ($isshort as $url_shorteners) {
    if(strstr($urlr, $protocol.$url_shorteners)) {
    echo "You entered an already shortened URL.<br>";
    echo "<a href='index.php'>Back</a>";
    die();
    }
}$query1 = "SELECT `rid`,`lkey` FROM `redirinfo` WHERE `rurl`='{$urlr}' AND iscustom='no';"; // Check if exists naturally
$result = $mysqli->query($query1);
$row = mysqli_fetch_assoc($result);
$existing = $row['rid'];
$lkey_ex = $row['lkey'];
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
if(!$existing || $customurl!="" || $ps=="s" || $lkey_ex) {
        // If does not exist or creating custom URL. If requesting a secret link, recreate as well.
		$query1 = "SELECT MAX(rid) AS `rid` FROM `redirinfo` WHERE `iscustom`='no';";
		$result = $mysqli->query($query1);
		$row = mysqli_fetch_assoc($result);
		$ridr = $row['rid'];
		// Check if next URL in base32 has been occupied by a custom url
        $q_checkbv = "SELECT `baseval` FROM `redirinfo` WHERE `rid`='{$ridr}';";
        $perform_cbv = $mysqli->query($q_checkbv);
        $cbvr = mysqli_fetch_assoc($perform_cbv);
        $based_val = $cbvr['baseval'];
        $nbnum = base_convert($based_val,36,10);
        $baseval = base_convert($nbnum+1,10,36);
        while (bve($baseval) == true) {
            $nbnum = base_convert($baseval,36,10);
            $baseval = base_convert($nbnum+1,10,36);

        }


        if($customurl!="") {
			// creating custom URL?
            $baseval = $customurl;
            $iscustom = "yes";
            $query = "SELECT `rid` FROM `redirinfo` WHERE `baseval`='{$customurl}';"; //check if baseval used already
            $result = $mysqli->query($query);
            $row = mysqli_fetch_assoc($result);
            $custom_existing = $row['rid'];
            if($custom_existing) {
                echo "The custom shorturl ending you specified is already in use. <a href='index.php'>Try again</a>";
                die();
            }
        }
        if($ps == "p" || !$ps) {
			$query2 = "INSERT INTO `redirinfo` (baseval,rurl,ip,user,iscustom,country) VALUES ('{$baseval}','{$urlr}','{$ip}','{$userinfo['username']}','{$iscustom}','{$country_code}');";
        }
        else if($ps=="s") {
			$query2 = "INSERT INTO `redirinfo` (baseval,rurl,ip,user,iscustom,lkey,country) VALUES ('{$baseval}','{$urlr}','{$ip}','{$userinfo['username']}','{$iscustom}','{$rstr}','{$country_code}');";
			$baseval .= "?".$rstr;
        }

        $result2r = $mysqli->query($query2);// or showerror();
        $basewsa = base64_encode($wsa);
        $basebv =base64_encode($baseval);
        echo "<input type='hidden' value='$basebv' id='j' /><input type='hidden' value='$basewsa' id='k' />";
        echo $decodescript;
        echo "<div style='text-align:center'>URL: <input type='text' id='i' onselect=\"select_text();\" onclick=\"select_text();\" readonly=\"readonly\" class='form-control' value=\"Please enable Javascript\" />";
        }
else {
	// Already exists. Fetch from DB and send over.
    $query1 = "SELECT `baseval` FROM `redirinfo` WHERE `rurl`='{$urlr}' AND iscustom='no'";
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
