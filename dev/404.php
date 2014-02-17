<!-- polr 404 --><!DOCTYPE html>
<html>
    <head>
        <title>404</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="bootstrap.css"/>
            <link rel="stylesheet" href="main.css"/>
        </head>
        <body style="padding-top:60px">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <a class="navbar-brand" href="<?php require_once('req.php');echo 'http://'.$wsa;?>"><?php echo $wsn;?></a>
                <ul class="nav navbar-nav">
                    <li><a href="//github.com/Cydrobolt/polr">Polr Github</a></li>
                </ul>
            </div>
            <div class="container">
                <div class="jumbotron" style="text-align:center; padding-top:80px; background-color: rgba(0,0,0,0);">
                    <h1>404</h1><br><h2>You step in the stream</h2><h2>But the water has moved on.</h2><h2>This page is not here</h2></div>
            </div>
                       <footer>
                <p><?php echo $footer;?></p>
            </footer>
        </div>
    </body>
</html>


