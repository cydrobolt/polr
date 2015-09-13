<!--
# Copyright (C) 2013-2015 Chaoyi Zha
# Polr is an open-source project licensed under the GPL.
# The above copyright notice and the following license are applicable to
# the entire project, unless explicitly defined otherwise.
# http://github.com/cydrobolt/polr
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or (at
# your option) any later version.
# See http://www.gnu.org/copyleft/gpl.html  for the full text of the
# license.
#
-->

<!DOCTYPE html>
<html>
<head>
    <title><?php require_once('config.php');echo $wsn;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php if (!$theme) {echo 'css/bootstrap.css';}else {echo $theme;}?>"/>
    <link rel="stylesheet" href="css/main.css"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />


</head>
<body style="padding-top:60px">
    <div class="container-fluid">
        <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><?php require_once('config.php');echo $wsn;?></a>
            </div>

            <ul id="navbar" class="nav navbar-collapse collapse navbar-nav" id="nbc">
                <li><a href="about.php">About</a></li>
                <li class="visible-xs"><a href="login.php">Sign In</a></li>
                <li class="visible-xs"><a href="admin/index.php">Dashboard</a></li>
            </ul>
            <ul id="navbar" class="nav pull-right navbar-nav hidden-xs">
                <?php require_once('lib-auth.php');
                $polrauth = new polrauth();
                $polrauth->headblock(); ?>
                <?php require_once('config.php'); if ($regtype != 'none'){ echo '<li><a href="register.php">Sign Up</a></li>';}?>
                <li class="divider-vertical"></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                    <div class="dropdown-menu pull-right" id="dropdown" style="padding: 15px; padding-bottom: 0px;">
                        <h2>Login</h2>
                        <form action="handle-login.php" method="post" accept-charset="UTF-8">
                            <input id="user_username" style="margin-bottom: 15px;" type="text" name="username" placeholder='Username' size="30" class="form-control">
                            <input id="user_password" style="margin-bottom: 15px;" type="password" name="password" placeholder='Password' size="30" class="form-control">
                            <input class="btn btn-success form-control" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="login" value="Sign In">
                            <br><br>
                        </form>
                    </div>
                </li>
                <?php $polrauth->headendblock(); ?>

            </ul>
        </div>
    </div>
        <div class="container">
            <div class="jumbotron" style="text-align:left; padding-top:5px; background-color: rgba(0,0,0,0);">
