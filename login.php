<?php
require_once('lib-auth.php');
$polrauth = new polrauth();
if ($polrauth->islogged() != false) {
	header("Location: ucp.php");
	die();
}
require_once('layout-headersm.php');


echo '
<div style="text-align:center">
  <h1>Login</h1><br/><br/>
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <form action="handle-login.php" method="POST"><b>Username:</b><br/>
      <input type="text" name="username" id="username" class="form-control"/><br/><b>Password:</b><br/>
      <input type="password" name="password" id="password" class="form-control"/><br />
      <input id="remember_me" style="padding-botton: 15px" type="checkbox" name="remember_me" value="remember_me" size="30" /> <b>Remember Me</b>
	<br /><br />
      <input type="submit" id="submit" style="width:80%" value="Login" class="btn btn-success"/>
    </form>
  </div>
  <div class="col-md-2"></div>
</div>';
require_once('layout-footerlg.php');
