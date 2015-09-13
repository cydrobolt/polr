<?php
/*
# Copyright (C) 2013-2015 Chaoyi Zha
# Polr is an open-source project licensed under the GPL.
# The above copyright notice and the following license are applicable to
# the entire project, unless explicitly defined otherwise.
# http://github.com/cydrobolt/polr
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or (at
# your option) any later version.
# See http://www.gnu.org/copyleft/gpl.html  for the full text of the
# license.
#
*/


/*
 * API Reference:
 * POST/GET arguments:
 * Required: url - the url to perform action on
 * Required: apikey - the APIKey provided for authentication
 * Required: action - action to perform, either lookup or shorten
 * Optional: temp - whether to treat the URL as temporary or not
 */
$reqargs['nosession'] = true;
require_once('lib-core.php'); //Fetch Config
// require_once('dnsbl.php'); //Load Google SafeBrowsing Script

$protocol = '://';
if (!strstr($_REQUEST['url'], $protocol)) {
    $urlr = "http" . $protocol . $_REQUEST['url']; //add http:// if :// not there
}

// $dnsbl = new dnsbl(); //create a Google Safe Browsing object

if (is_string($_REQUEST['apikey']) && is_string($_REQUEST['action']) && is_string($_REQUEST['url'])) {
    $apikey = $mysqli->real_escape_string($_REQUEST['apikey']); //Sanitize input
    $action = $mysqli->real_escape_string($_REQUEST['action']);
    $url_api = $mysqli->real_escape_string($_REQUEST['url']);
} else {
    header("HTTP/1.0 400 Bad Request");
    die("Error: No value specified, or wrong data type.");
}

// Check API key
$query = "SELECT `valid`,`quota` FROM `api` WHERE apikey='{$apikey}'";
$result = $mysqli->query($query) or showerror();
$validrow = mysqli_fetch_assoc($result);
$userquota = $validrow['quota'];
//check if valid
if (!$validrow['valid']) {
    $api_key_valid = 0;
} else {
    $api_key_valid = 1;
}

if (!$api_key_valid) {
    header("HTTP/1.0 401 Unauthorized"); // Invalid key received
    die('401 Unauthorized');
}

if (!filter_var($url_api, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && $action != "lookup") {
    header("HTTP/1.0 400 Bad Request");
    echo "Error: URL is not valid"; // URL not well formatted, but allow if action is lookup
    die();
}

// Check URL against Google Safe Browsing

/*
$isbl = $dnsbl->isbl($url_api);
if ($isbl === "malware" || $isbl === "phishing") {
    header("HTTP/1.0 401 Unauthorized");
    echo "Polr does not shorten potentially malicious URLs";
    die();
}
*/

function lookup($urltl) {
    global $mysqli;
    $val = $mysqli->real_escape_string($urltl);
    $query = "SELECT rurl FROM redirinfo WHERE baseval='{$val}'";
    $result = $mysqli->query($query) or die("QUERY ERROR");
    $row = mysqli_fetch_assoc($result);
    return $row['rurl'];
}

function exquota($apikey, $quota) {
    /*
        Check if a user is exceeding their allocated quota.
        Returns false if they are not exeeding their quota, or
        true if they are past their quota.
    */
    if ($quota < 1) {
        return false; // if quota is negative, then no quota
    }

    global $mysqli;
    $last_min = time()-60;
    $query = "SELECT `rurl` FROM `redirinfo` WHERE user='APIKEY-{$apikey}' AND UNIX_TIMESTAMP(date) > $last_min;";
    $result = $mysqli->query($query) or showerror();
    $total_queries = $mysqli->affected_rows; // get the amount of new URLs created in the past minute
    $query = "SELECT `rurl` FROM `redirinfo-temp` WHERE user='APIKEY-{$apikey}' AND UNIX_TIMESTAMP(date) > $last_min;";
    $result = $mysqli->query($query) or showerror();
    $total_queries_temp = $mysqli->affected_rows;

    if (($total_queries+$total_queries_temp) >= $quota) {
        return true;
    }
    else {
        return false;
    }

}

function shorten($urlr, $t = 'false') {
    global $mysqli;
    global $wsa;
    global $apikey;
    global $ip;

    $protocol = '://';
    $isshort = array('polr.me', 'bit.ly', 'is.gd', 'tiny.cc', 'adf.ly', 'ur1.ca', 'goo.gl', 'ow.ly', 'j.mp', 't.co');
    foreach ($isshort as $url_shorteners) {
        if (strstr($urlr, $protocol . $url_shorteners)) {
            header("HTTP/1.0 400 Bad Request");
            die("400 Bad Request (URL Already a ShortURL)");
        }
    }
    $query1 = "SELECT `rid` FROM `redirinfo` WHERE `rurl`='{$urlr}' AND `iscustom`='no'";
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $existing = $row['rid'];
    if (!$existing) {
        if ($t != 'false') {
            //if tempurl
            $query1 = "SELECT MAX(rid) AS rid FROM `redirinfo-temp`;";
            $result = $mysqli->query($query1);
            $row = mysqli_fetch_assoc($result);
            $ridr = $row['rid'];
            $baseval = "t-" . (string) (base_convert($ridr + 1, 10, 36));
            $query2 = "INSERT INTO `redirinfo-temp` (baseval,user,rurl,ip) VALUES ('{$baseval}','APIKEY-{$apikey}','{$urlr}','{$ip}');";
        } else {
            //if NOT tempurl
            $query1 = "SELECT MAX(rid) AS rid FROM redirinfo;";
            $result = $mysqli->query($query1);
            $row = mysqli_fetch_assoc($result);
            $ridr = $row['rid'];
            $baseval = base_convert($ridr + 1, 10, 36);
            $query2 = "INSERT INTO redirinfo (baseval,user,rurl,ip) VALUES ('{$baseval}','APIKEY-{$apikey}','{$urlr}','{$ip}');";
        }

        $result2r = $mysqli->query($query2) or showerror();
        return "http://{$wsa}/{$baseval}";
    } else {
        $query1 = "SELECT baseval FROM redirinfo WHERE rurl='{$urlr}'";
        $result = $mysqli->query($query1);
        $row = mysqli_fetch_assoc($result);
        $baseval = $row['baseval'];
        return "http://{$wsa}/{$baseval}";
    }
}
/*
 * Check whether the user is exceeding his quota
 */

$isexeeding = exquota($apikey, $userquota);
if ($isexeeding) {
    header("HTTP/1.0 503 Service Unavailable");
    die('Hey, slow down! Exeeding your per minute quota. Try again in around a minute.');
}

// API execute actions. Promised, no more checks :)

if ($action == "shorten") {
    if (isset($_REQUEST['temp'])) {
        $ist = $mysqli->real_escape_string($_REQUEST['temp']);
        $ist = strtolower($ist);
    }
    if (($ist == 'true') || ($ist == 'false')) {
        echo shorten($url_api, $ist);
        die();
    }
    echo shorten($url_api);
    die();
} else if ($action == "lookup") {
    $looked_up_url = lookup($url_api);
    if (!$looked_up_url) {
        header("HTTP/1.0 404 Not Found");
        die("404 Not Found");
    } else {
        echo $looked_up_url;
    }
    die();
} else {
    die("Invalid Action");
}
