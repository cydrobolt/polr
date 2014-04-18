<!-- polr about -->

<!DOCTYPE html>
<html>
    <head>
        <title>About Polr</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="bootstrap.css"/>
        <link rel="stylesheet" href="main.css"/>
        <link rel="shortcut icon" href="favicon.ico">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    </head>
    <body style="padding-top:60px">
        <div class="container-fluid">
            <div class="navbar navbar-inverse navbar-fixed-top"><div class="navbar-header"><a class="navbar-brand" href="index.php">Polr</a></div>
                <!--<a class="btn btn-navbar btn-default" data-toggle="collapse" data-target="#nbc">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>-->

                <ul class="nav navbar-collapse navbar-nav" id="nbc">
                    <li><a href="//github.com/Cydrobolt/polr">Github</a></li>
                    <li><a href="//project.polr.cf">Source</a></li>
                    <li><a href="about.php">About</a></li><li><a href="contact.php">Contact</a></li>
                </ul>
                <ul class="nav pull-right navbar-nav">
                    <?php
                    require_once('polrauth.php');
                    $polrauth = new polrauth();
                    $polrauth->headblock();
                    ?>
                    <li><a href="register.php">Sign Up</a></li>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                        <div class="dropdown-menu" id="dropdown" style="padding: 15px; padding-bottom: 0px; color:white;">
                            <h2>Login</h2>
                            <form action="loginproc.php" method="post" accept-charset="UTF-8">
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
            <div class="jumbotron" style="text-align:center; padding-top:80px; background-color: rgba(0,0,0,0);">
                <h1>Polr</h1><br><p>Polr is an <a href='http://en.wikipedia.org/wiki/Open_source'>open source</a> URL shortener developed by <a href='http://cydrobolt.com'>Cydrobolt</a>. <br>If you would like to contribute, please join us at #polr on irc.freenode.net:6667 (<a href='http://webchat.freenode.net/?channels=#polr'>Webchat</a>)<br>If you would like to use Polr on your own website, visit the Polr <a href='//project.polr.cf'>project website</a>. <br>The code is hosted at Github - <a href='http://github.com/Cydrobolt/polr'>here</a></p>
                <br><h2>Why Polr?</h2><br>
                <div class="col-md-4"><b>Simple and minimalistic</b>
                    <p>Polr is developed with minimalism and simplicity in mind. Unlike many other URL Shorteners, 
                        Polr is free of obstructing ads, excess text, and extravagant pictures or alerts.
                    </p>
                </div>
                <div class="col-md-4"><b>Reliable and powerful</b>
                    <p>Polr is hosted on Red Hat's powerful infrastructure. Though we do not guarantee uptime, Polr's minimalistic philosophy and capable infrastructure enables users to shorten and access links with a low latency.</p>
                </div>
                <div class="col-md-4"><b>Actually short URLs</b>
                    <p>While many URL shorteners claim to 'shorten' your URLs, the URLs they create are often longer than 11 characters. Polr strives to create truly short URLs.</p>
                </div>
            </div>
        </div>
        <br>
        <div class="container jumbotron" style='text-align: center'>
            <div class="col-md-4"><b>Tinyurl.com</b><br>example.com/example -> tinyurl.com/3xsrg5</div>
            <div class="col-md-4"><b style='color:green'>Polr.cf</b><br>example.com/example -> polr.cf/6l</div>
            <div class="col-md-4"><b>Bit.ly</b><br>example.com/example -> bit.ly/1gcaB14</div>

        </div>
        <div style='padding-left: 8%'>
            <hr>
            <span style='font-size: 75%'>
                Bitly &trade; is a trademark owned by Bitly Inc., Tinyurl &trade; is a trademark owned by Gilby Productions. All trademarks are owned by their respective owners. Polr is not in any way affiliated with Gilby Productions or Bitly Inc.
            </span>    
            <br><br>
            <span style='font-size: 75%'>
                <p>&copy; Copyright 2014 Polr - <a href='privacypolicy.php'>Privacy Policy</a> - <a href='tos.php'>Terms of Service</a></p>
            </span>

        </div>
    </div>
</body>
</html>
