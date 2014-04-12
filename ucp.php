<?php

require_once 'req.php';
require_once 'headerpage.php';
require_once 'polrauth.php';
$auth = new polrauth();
$isadmin = $auth->isadminli();
if (!is_array($auth->islogged())) {
    echo "<h3>You must login to access this page.</h3><br><a href='index.php'>Home</a>";
    require_once 'footer.php';
    die(); //END NOT LOGGED IN PORTION
} else {
    $userinfo = $auth->islogged();

    function fetchurls($lstart=0) {
        global $userinfo;
        global $mysqli;
        $sqr = "SELECT `baseval`,`rurl`,`date` FROM `redirinfo` WHERE user = '{$mysqli->real_escape_string($userinfo['username'])}' LIMIT {$lstart} , 50;";
        $res = $mysqli->query($sqr);
        $links = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $linkshtml = '<table class="table table-hover"><tr><th>Link ending</th><th>Long Link</th><th>Date (EST - NA)</th></tr>';
        foreach ($links as $link) {
            $linkshtml = $linkshtml . "<tr><td>" . $link['baseval'] . '</td>';
            $linkshtml = $linkshtml . "<td>" . substr($link['rurl'], 0, 170) . '</td>';
            $linkshtml = $linkshtml . "<td>" . $link['date'] . '</td></tr>';
        }
        $linkshtml = $linkshtml . "</tr></table>";
        return $linkshtml;
    }
    $linkshtml = fetchurls();
    echo "<script src='ucptabs.js'></script>";
    echo "<h3>Polr Dashboard</h3><br>";
    echo '<ul class="nav nav-tabs" id="tabsb">
        <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
        <li><a href="#links" data-toggle="tab">My links</a></li>
        <li><a href="#messages" data-toggle="tab">Messages</a></li>
        <li><a href="#settings" data-toggle="tab">Settings</a></li>';
    if ($isadmin == true) {
        echo '<li><a href="#adminpanel" data-toggle="tab">Admin Panel</a></li>';

        function fetchurlsadmin($lstart=0,$limit=360) {
            global $mysqli;
            $sqr = "SELECT `baseval`,`rurl`,`date`,`user`,`ip` FROM `redirinfo` LIMIT {$lstart} , {$limit};";
            $res = $mysqli->query($sqr);
            $links = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $linkshtml = '<table class="table table-hover"><tr><th>Link ending</th><th>Long Link</th><th>Date (EST - NA)</th><th>Link Owner</th><th>IP</th></tr>';
            foreach ($links as $link) {
                $linkshtml = $linkshtml . "<tr><td>" . $link['baseval'] . '</td>';
                $linkshtml = $linkshtml . "<td>" . substr($link['rurl'], 0, 170) . '</td>';
                $linkshtml = $linkshtml . "<td>" . $link['date'] . '</td>';
                $linkshtml = $linkshtml . "<td>" . $link['user'] . '</td>';
                $linkshtml = $linkshtml . "<td>" . $link['ip'] . '</td></tr>';
            }
            $linkshtml = $linkshtml . "</tr></table>";
            return $linkshtml;
        }
        function fetchusersadmin($lstart=0) {
            global $mysqli;
            $sqr = "SELECT `username`,`email`,`valid` FROM `auth` LIMIT {$lstart} , 360;";
            $res = $mysqli->query($sqr);
            $links = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $usershtml = '<table class="table table-hover"><tr><th>Username</th><th>Email</th><th>Activated?</th></tr>';
            foreach ($links as $link) {
                $usershtml = $usershtml . "<tr><td>" . $link['username'] . '</td>';
                $usershtml = $usershtml . "<td>" . $link['email'] . '</td>';
                $usershtml = $usershtml . "<td>" . $link['valid'] . '</td></tr>';
            }
            $usershtml = $usershtml . "</tr></table>";
            return $usershtml;
        }
        $usersadmin = fetchusersadmin();
        $linksadmin = fetchurlsadmin();

    }
    
    echo '</ul>';
    echo '<div class="tab-content">
  <div class="tab-pane active" id="home"><br><h2>Welcome to Polr. Made with <3, <code>$ bash</code>, and lots of <code>git push</code>es</div>
  <div class="tab-pane" id="links"><br>' . $linkshtml . '</div>
  
  <div class="tab-pane" id="messages"><br><b>You have <span style="color:green">no new messages</span>.</b></div>
  <div class="tab-pane" id="settings"><br><b>Settings:</b><br><br><form action="ucp.php" method="POST">
  Current Password: <input type="password" name="cpw" /><br>New Password: <input type="password" name="npw" />
  <br><br>Theme: <select><option value="Default">Aurora (Default)</option><option value="flatly">Midnight Slopes</option>
  <option value="yeti">Metro Shift</option></select><br>(settings not changeable yet)
  <br><br><input type="submit" class="btn-success btn-lg" disabled="disabled"/>
</form></div>';
    if ($isadmin == true) {
        foreach ($_SESSION as $sevar) {
            $sessiondump = $sessiondump.'<br>'.$sevar;
        }
        echo '<div class="tab-pane" id="adminpanel"><br>Polr Links - Limited @ 360:'.$linksadmin.'<br>Polr Users - Limited @ 360:'.$usersadmin.'<br>Debug Variables: <br>Default IP Fetch: '.$ip.'<br>X-Forwarded-For:'.@$_SERVER['X-Forwarded-For'].'<br>Forwarded-For'.@$_SERVER['forwarded-for'];//<br><br>Session Dump:'.$sessiondump.'</div>';
    }


    echo '</div>';
}

//require_once 'footerpage.php';
