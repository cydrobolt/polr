<?php
//POLR CONFIGURATION

$host = "localhost"; //Enter Mysql host address
$user = "root"; //Mysql user
$passwd = ""; //Mysql user password
$db = "polr"; //Mysql DB name
$wsa = "localhost/polr/php"; //Address of website : e.g polr.cf - do not include http://
$ip = $_SERVER['REMOTE_ADDR']; //How Polr should fetch the user's ip - some hosts require you to use getenv()
@(include('ovr.php')); //If OVR.php is there, load that config instead



//connect to mysql with $mysqli variable
$mysqli = new mysqli($host,$user,$passwd,$db) or die("Error : Could not establish database connection");

//SQL Functions

//Sanitize input when using sqlrun!
function sqlrun ($query) {
    global $mysqli;
    $queryrs = $query;
    $resultrs = $mysqli->query($queryrs) or die("ERROR in $query");
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
/*
$(function() {
    console.log( "ready!" );
    var todec = $("#todec").val();
    var decoded = window.atob(todec);
    $(".form-control").val("http://polr.cf/"+decoded);
});
*/
