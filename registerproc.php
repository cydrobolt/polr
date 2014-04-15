<?php
require_once('password.php'); //password hashing lib - crpypt forward compat
require_once('req.php');
require_once("ayah.php");
require_once('sgmail.php');
$sgmail = new sgmail();
$ayah = new AYAH();
if (array_key_exists('polrsubmit', $_POST))
{
          // Use the AYAH object to see if the user passed or failed the game.
          $score = $ayah->scoreResult();
          if ($score)
          {
              $passed = 1;
              }
          else
          {
                  require_once 'req.php';
                  require_once 'header.php';
                  echo "Sorry, but we were not able to verify you as human. Please try again. <br><br><a href='register.php'>Go Back</a>";
                  require_once 'footer.php';
                  die(); //prevent user from registering
                  
          }
}

$isemail = filteremail($_POST['email']);

if(!$isemail) {
    require_once 'header.php';
    echo "Please enter a valid email. <br><br><a href='register.php'>Go Back</a>";
    require_once 'footer.php';
    die(); //prevent user from registering
}
if((strlen($_POST['username'])>15) || (strlen($_POST['password'])>25) || (strlen($_POST['email'])>50)) {
    require_once 'header.php';
    echo "Your username must not be over 15 characters, password must be under 25 characters but over 6 characters, and email must be under 50 charcaters. <br><br><a href='register.php'>Go Back</a>";
    require_once 'footer.php';
    die(); //prevent user from registering
}
if(strlen($_POST['username'])==0 || strlen($_POST['password'])<4 || strlen($_POST['email'])==0) {
    require_once 'header.php';
    echo "Fields may not be left blank, password must be over 4 characters. <br><br><a href='register.php'>Go Back</a>";
    require_once 'footer.php';
    die(); //prevent user from registering   
}
if(!ctype_alnum($_POST['username'])) {
    require_once 'header.php';
    echo "Your username must be alphanumerical (numbers and letters only). <br><br><a href='register.php'>Go Back</a>";
    require_once 'footer.php';
    die(); //prevent user from registering 
}


$salt = mcrypt_create_iv(23, MCRYPT_DEV_URANDOM); //create salt
$rstr = mcrypt_create_iv(23, MCRYPT_DEV_URANDOM);
$reg['username'] = $mysqli->real_escape_string($_POST['username']);
$reg['email'] = $mysqli->real_escape_string($_POST['email']);
$reg['password'] = $mysqli->real_escape_string($_POST['password']);
$reg['rkey'] = sha1($reg['username'].$reg['email'].date('zjDygs').$rstr);

//check if already exists

$ireg['1'] = sqlex('auth','email','username',$reg['username']);
$ireg['2'] = sqlex('auth','username','email',$reg['email']);
$ireg['3'] = sqlfetch('auth','valid','email',$reg['username']);

if(($ireg['1']==true || $ireg['2']==true)&&$ireg['3']==1) {
    require_once 'header.php';
    echo "Username/email already in use. <br><br><a href='register.php'>Go Back</a>";
    require_once 'footer.php';
    die(); //prevent user from registering   
}

$opts = [
    'cost' => 10,
    'salt' =>$salt
];
$hashed = password_hash($reg['password'],PASSWORD_BCRYPT,$opts);
$reg['password'] = $hashed;

$qr = "INSERT INTO auth (username,email,password,rkey,valid) VALUES ('{$reg['username']}','{$reg['email']}','{$hashed}','{$reg['rkey']}','0');";
$rr = $mysqli->query($qr) or showerror();
$sglink = "http://polr.cf/activate.php?key=".$reg['rkey'].'&user='.$reg['username'];
$sgmsg = "Please validate your Polr Account by clicking the link below or pasting it into your browser:<br>"
        .'<a href="'.$sglink.'">'.$sglink.'</a>'
        . "<br><br>If you did not register at Polr (<a href='//polr.cf'>Polr.cf</a>), please disregard this email."
        . "<br>";
$to = $reg['email'];
$sm = $sgmail->sendmail($to, 'Polr Account Validation',$sgmsg);


require_once 'header.php';
echo "Thanks for registering. Check your email for an activation link. You must activate your account before logging in (top right corner)";
require_once 'footer.php';
die();




