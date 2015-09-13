<?php
require_once 'config.php';

if ($fpass == false) {
    die('This service was disabled by the site owner. ');
}

require_once 'lib-core.php'; // require core libs
require_once 'lib-auth.php'; // require auth libs
require_once 'helpers/helper-mailsend.php'; // require mail libs
require_once 'lib-password.php'; // require password encryption libs
require_once 'fpasslib.php'; // require fpass functions

$polrauth = new polrauth();
$fpass = new fpass();
require_once 'layout-headerlg.php';
if (isset($_POST['rnpass']) && isset($_POST['npass']) && isset($_POST['crkey']) && isset($_POST['cuser'])) {
    // if submitting new password
    $ckey = $mysqli->real_escape_string($_POST['crkey']);
    $rnpass = $mysqli->real_escape_string($_POST['rnpass']);
    $cuser = $mysqli->real_escape_string($_POST['cuser']);
    $npass = $mysqli->real_escape_string($_POST['npass']);
    $userinfoc = $polrauth->getinfomu($cuser); // fetch the user's information
    if ($userinfoc == false) {
        echo "<h2>That username is not associated with any account. Please try again.</h2>"
        . "<br />"
        . "<a href='forgotpass.php'>Back</a>";
        require_once 'layout-footerlg.php';
        die();
    }
    if ($userinfoc == false) {
        // if user does not exist
        require_once 'layout-headerlg.php';
        echo "<h2>User or key invalid or already used.</h2>";
        require_once 'layout-footerlg.php';
        die();
    }
    if ($userinfoc['rkey'] == $_POST['crkey']) {
        // if the rkey is correct
        if ($npass != $rnpass) {
            // if new pass & repeat don't match
            require_once 'layout-headerlg.php';
            echo "<h2>Passwords don't match. Try again. (click the link in the email again)</h2>";
            require_once 'layout-footerlg.php';
            die();
        } else {
            // everything is as expected, perform password reset
            $fpass->changepass($npass, $cuser); // update the user's password
            $polrauth->crkey($cuser); // update their reset token
            require_once 'layout-headerlg.php';
            echo "<h2>Password changed.</h2>";
            require_once 'layout-footerlg.php';
            die();
        }
    }
}
$fpass = new fpass();
if (isset($_GET['key']) && isset($_GET['username'])) {
    $username = $mysqli->real_escape_string($_GET['username']);
    $userinfoc = $polrauth->getinfomu($username);
    if ($userinfoc == false) {
        echo "<h2>That username is not associated with any account. Please try again.</h2>"
        . "<br />"
        . "<a href='forgotpass.php'>Back</a>";
        require_once 'layout-footerlg.php';
        die();
    }
    if ($userinfoc == false) {
        // if the user does not exist
        require_once 'layout-headerlg.php';
        echo "<h2>User or key invalid or already used.</h2>";
        require_once 'layout-footerlg.php';
        die();
    }
    if ($userinfoc['rkey'] == $_GET['key']) {
        require_once 'layout-headerlg.php';
        echo "<h2>Change Password for {$_GET['username']}</h2>";
        echo "<form action='forgotpass.php' method='POST' class='form-inline' role='form'>"
        . "<input type='password' name='npass' id='npass' placeholder='New Password' style='width: 250px;' class='form-control' size='50'/>"
        . "<input type='password' name='rnpass' id='rnpass' placeholder='Repeat New Password' style='width: 250px;' class='form-control' size='50'/>"
        . "<input type='hidden' name='crkey' value='{$_GET['key']}' />"
        . "<input type='hidden' name='cuser' value='{$username}' />"
        . "<br /><div id='warn' style='padding-top: 30px;'></div></br />"
        . "<input type='submit' id='submit' class='form-control' style='width: 450px;' value='Change Password' />"
        . "</form>";
        echo "<script src='fpass.js'></script>";
        require_once 'layout-footerlg.php';
        die();
    }
}

@$email = $_POST['email'];
if (!$email) {
    echo "<h2>Forgot your password?</h2>"
    . "<br/ >"
    . "<form action='forgotpass.php' method='POST' style='margin:0 auto; width: 450px'>"
    . "<input type='text' class='form-control' style='width: 450px;' name='email' placeholder='Email...' /><br />"
    . "<input type='submit' name='fpasssubmit' class='form-control' style='width: 450px;' value='Get a password reset email' />"
    . "</form>";
    require_once 'layout-footerlg.php';
    die();
}
if (strlen($email) < 5) {
    echo "<h2>Forgot your password?</h2>"
    . "<br/ >"
    . "<form action='forgotpass.php' method='POST'>"
    . "<input type='text' name='email' placeholder='Email...' />"
    . "<input type='submit' name='fpasssubmit' value='Get a password reset email' />"
    . "</form>";
    require_once 'layout-footerlg.php';
    die();
}
$email = $mysqli->real_escape_string($_POST['email']);
$userinfo = $polrauth->getinfome($email);
if ($userinfo == false) {
    echo "<h2>That email is not associated with any account. Please try again.</h2>"
    . "<br />"
    . "<a href='forgotpass.php'>Back</a>";
    require_once 'layout-footerlg.php';
    die();
}
$rkey = $userinfo['rkey'];
$username = $userinfo['username'];
$fpass->sendfmail($email, $username, $rkey);
echo "Email successfully sent. Check your inbox for more info.";
require_once 'layout-footerlg.php';
