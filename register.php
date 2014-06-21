<?php
/*
 * Registration Page
 */
require_once('req.php');
require_once('headerpage.php');

echo "<h2 style='color:green'>Register</h2>";
echo "<form action='registerproc.php' method='POST'>"
. "<br>Username: <input type='text' name='username' class='form-control' placeholder='Username' />"
        . "<br>Password: <input type='password' name='password' class='form-control' placeholder='Password' />"
        . "<br>Email: <input type='email' name='email' class='form-control' placeholder='Email' />"
        
        . "<br><input type=\"submit\" name=\"polrsubmit\" class=\"btn btn-default btn-warning\" value=\"Register\"/>";

require_once('footerpage.php');