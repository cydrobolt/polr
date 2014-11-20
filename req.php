<?php
@(require_once('config.php'));
include('version.php');
$debug = 0; // Set to 1 in order to enable debug mode (shows sensitive database info), use for troubleshooting
$footer = "&copy; Copyright 2014 $wsn. Powered by <a href='http://github.com/cydrobolt/polr'>Polr</a> ver $version build $reldate";
$hidefooter = true; // Let's hide this for now
//connect to mysql with $mysqli variable
$mysqli = new mysqli($host, $user, $passwd, $db) ;
if ($mysqli->connect_errno) {
    echo "Database error. If you are a member of the general public, contact an administrator to solve this issue.
    If you are the administrator of this website, please make sure your database is turned on and that credentials are correct.";
    die();
}
// Attempt to set Charset as UTF8 to avoid real_escape_string vulnerabilities
if (!$mysqli->set_charset("utf8")) {
    $insecure = TRUE;
} else {
    $insecure = FALSE;
}

function autoloader($class) {
        include $class . '.php';
}

spl_autoload_register('autoloader');
session_start();

function sqlex($table, $rowf, $where, $wval) {
    global $mysqli; //Import var into function
//Sanitize strings
    $rowfs = $mysqli->real_escape_string($rowf);
    $tables = $mysqli->real_escape_string($table);
    $wheres = $mysqli->real_escape_string($where);
    $wvals = $mysqli->real_escape_string($wval);
    $q2p = "SELECT {$rowfs} FROM {$tables} WHERE {$wheres}=?";
	$stmt = $mysqli->prepare($q2p);
	$stmt->bind_param('s', $wvals);
	$stmt->execute();
	$result = $stmt->get_result();
    $numrows = $result->num_rows;
    if (!$numrows) {
        return false;
    } else {
        return true;
    }
}

function sqlfetch($table, $rowf, $where, $wval) {
    global $mysqli;

    $rowfs = $mysqli->real_escape_string($rowf);
    $tables = $mysqli->real_escape_string($table);
    $wheres = $mysqli->real_escape_string($where);
    $wvals = $mysqli->real_escape_string($wval);

    //$query = "SELECT $rowfs FROM $tables WHERE $wheres='$wvals'";
    $q2p = "SELECT {$rowfs} FROM {$tables} WHERE {$wheres}=?";
	$stmt = $mysqli->prepare($q2p);
	$stmt->bind_param('s', $wvals);
	$stmt->execute();
	$result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);
    return $row[$rowf];
}

//SQL Functions
//Sanitize input when using sqlrun!
function sqlrun($query) {
    global $mysqli;
    $queryrs = $query;
    $resultrs = $mysqli->query($queryrs) or ($err =  $mysqli->error);
    if (strstr($err, "already exists")) {
        echo "<br />Could not create tables because the database already has Polr tables (perhaps from a previous installation?). Delete the existing Polr table and try again. You can also export the database and restore it after installation, however, the old database may not be compatible. ";
        die();
    }
    return true;
}


function showerror() {
	//Show an error, and die. If Debug is on, show SQL error message
    global $debug;
    global $mysqli;
    echo "There seems to be a problem. Contact an administrator to report this issue.";
    if ($debug == 1) {
        echo "<br>Error:<br>";
        echo $mysqli->error;
    }
    die();
}


function filterurl($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
        return false;
    } else {
        return true;
    }
}
function filteremail($email) {
	// Validate an email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}
