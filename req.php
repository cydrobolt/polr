<?php
//POLR CONFIGURATION LOADING


@(require_once('config.php'));
include('version.php');
$footer = "&copy; Copyright 2014 $wsn. Powered by <a href='http://github.com/cydrobolt/polr'>Polr</a> ver $version build $reldate";

//connect to mysql with $mysqli variable
$mysqli = new mysqli($host,$user,$passwd,$db) or $wp=1; //If cannot connect, then set var $wp to 1

//SQL Functions

//Sanitize input when using sqlrun!
function sqlrun ($query) {
    global $mysqli;
    $queryrs = $query;
    $resultrs = $mysqli->query($queryrs) or $mysqli->error;
    return true;
}

function sqlex ($table,$rowf,$where,$wval) {
global $mysqli; //Import var into function
//Sanitize strings
$rowfs = $mysqli->real_escape_string($rowf);
$tables = $mysqli->real_escape_string($table);
$wheres = $mysqli->real_escape_string($where);
$wvals = $mysqli->real_escape_string($wval);

$query = "SELECT $rowfs FROM $tables WHERE $wheres='$wvals'";
$result = $mysqli->query($query) or showerror();
$numrows = $mysqli->affected_rows;
if(!numrows) {
    return false;
}
else {
    return true;
}
}

function sqlfetch ($table,$rowf,$where,$wval) {
global $mysqli;

$rowfs = $mysqli->real_escape_string($rowf);
$tables = $mysqli->real_escape_string($table);
$wheres = $mysqli->real_escape_string($where);
$wvals = $mysqli->real_escape_string($wval);

$query = "SELECT $rowfs FROM $tables WHERE $wheres='$wvals'";
$result = $mysqli->query($query) or showerror();
$row = mysqli_fetch_assoc($result);
return $row[$rowf];
}
function showerror (){
echo "There seems to be a problem :'( *sniff* . Click > <a href='http://webchat.freenode.net/?channels=polr'>here</a> contact an administrator.";
die();
}

function filterurl ($url) {
    if(!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
        return false;
    }
    else {
        return true;
    }
}
