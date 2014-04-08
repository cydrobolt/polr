<?php
//mysqli connection info
$host = "##HIDDEN - STOP PEEKING! ##";
$user = "##HIDDEN - STOP PEEKING! ##";
$passwd = "##HIDDEN - STOP PEEKING! ##";
$db = "polr";
$wsa = "polr.cf";
$debug = 0;
$ip = $_SERVER['CF-Connecting-IP'];
$hp = "##HIDDEN - STOP PEEKING! ##";

function autoloader($class) {
        include $class . '.php';
}

spl_autoload_register('autoloader');

session_start();


//connect to mysql with $mysqli variable
$mysqli = new mysqli($host, $user, $passwd, $db) or die("Error : Could not establish database connection");

//SQL Functions
//Sanitize input when using sqlrun!
function sqlrun($query) {
    global $mysqli;
    $queryrs = $query;
    $resultrs = $mysqli->query($queryrs) or die("ERROR in $query");
    return true;
}

function sqlex($table, $rowf, $where, $wval) {
    global $mysqli; //Import var into function
//Sanitize strings
    $rowfs = $mysqli->real_escape_string($rowf);
    $tables = $mysqli->real_escape_string($table);
    $wheres = $mysqli->real_escape_string($where);
    $wvals = $mysqli->real_escape_string($wval);

    $query = "SELECT $rowfs FROM $tables WHERE $wheres='$wvals'";
    $result = $mysqli->query($query) or showerror();
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

    $query = "SELECT $rowfs FROM $tables WHERE $wheres='$wvals'";
    $result = $mysqli->query($query) or showerror();
    $row = mysqli_fetch_assoc($result);
    return $row[$rowf];
}

function showerror() {
    global $debug;
    global $mysqli;
    echo "There seems to be a problem :'( *sniff* . Click > <a href='http://webchat.freenode.net/?channels=polr'>here</a> contact an administrator.";
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
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
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
