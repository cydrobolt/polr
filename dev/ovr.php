<?php
//THIS CONFIG OVERRIDES THE REQ.PHP CONFIG - DO NOT USE IN PRODUCTION, DEV ONLY
//ERRORS COULD OCCUR IF YOU DO NOT CONFIGURE POLR'S REQ.PHP CONFIG CORRECTLY IN PRODUCTION
//Polr CONFIGURATION


$host = "localhost"; //Enter Mysql host address
$user = "root"; //Mysql user
$passwd = ""; //Mysql user password
$db = "polr"; //Mysql DB name
$wsa = "localhost/polr/php"; //Address of website : e.g polr.cf - do not include http://
$ip = $_SERVER['REMOTE_ADDR']; //How Polr should fetch the user's ip - some hosts require you to use getenv()
$ovr = 1;