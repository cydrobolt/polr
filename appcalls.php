<?php
/*
 * http://github.com/cydrobolt/polr
 */
//To use this, put this in your app
// --> include('appcalls.php'); if appcalls.php is in the same directory as the script calling it
// --> include('/path/to/appcalls.php'); if appcalls.php is in another dir

/* This script does not sanitize any input. It also doesn't check whether  
 * the person calling it has an API key or not. Please only use this
 * for shortening urls from your other scripts.
 */

//How to use:
/*
 * Simply include this script, then use the functions
 * lookup($url); to lookup the baseval (the letters following the slash, e.g polr.cf/<baseval>)
 *  of an url to find the longurl.
 * 
 * shorten($url); shortens a url, returns the shortened url.
 * 
 * The shortened url will be in a http://domain/baseval form. You will need the
 * .htaccess file provided in order to accomplish this. r.php must be in your 
 * domain root.
 * 
 */
/*
function lookup ($urltl) {
    global $mysqli;
    $val = $mysqli->real_escape_string($urltl);
    $query = "SELECT rurl FROM redirinfo WHERE baseval='{$val}'";
    $result = $mysqli->query($query) or die("QUERY ERROR");
    $row = mysqli_fetch_assoc($result);
    return $row['rurl'];
}

function shorten ($urlr) {
    global $mysqli;
    global $wsa;
    $query1 = "SELECT rid FROM redirinfo WHERE rurl='{$urlr}'";
    $result = $mysqli->query($query1);
    $row = mysqli_fetch_assoc($result);
    $existing = $row['rid'];
    if(!$existing) {
	$query1 = "SELECT MAX(rid) AS rid FROM redirinfo;";
	$result = $mysqli->query($query1);
	$row = mysqli_fetch_assoc($result);
	$ridr = $row['rid'];
	$baseval = base_convert($ridr+1,10,36);
        $query2 = "INSERT INTO redirinfo (baseval,rurl) VALUES ('{$baseval}','{$urlr}');";
        $result2r = $mysqli->query($query2) or showerror();
        return "http://{$wsa}/{$baseval}";
        }
    else {
        $query1 = "SELECT baseval FROM redirinfo WHERE rurl='{$urlr}'";
        $result = $mysqli->query($query1);
        $row = mysqli_fetch_assoc($result);
	$baseval = $row['baseval'];
	return "http://{$wsa}/{$baseval}";
    }
}
*/

// Unsafe for now
