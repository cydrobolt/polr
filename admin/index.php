<?php
require_once '../lib-core.php';
require_once 'headerpage.php';
require_once '../lib-auth.php';
$auth = new polrauth();
$isadmin = $auth->isadminli();

@$users_page = $_GET['upage'];
@$links_page = $_GET['lpage'];
@$admin_links_page = $_GET['alpage'];

function table_length($table_name) {
    global $mysqli;
    if ($table_name == "redirinfo") {
        $colname = "rurl";
    }
    else if ($table_name == "auth") {
        $colname = "username";
    }
    $result = $mysqli->query("SELECT COUNT(`{$colname}`) FROM `{$table_name}`;") or showerror();
    $count = ($result->fetch_assoc());
    $count = (double) $count["COUNT(`{$colname}`)"];

    $pages = $count / 30;

    return ceil($pages);
}
function paginate_table($current_page = 0, $total_pages = 0, $pag_name, $sel_name) {
    $markup = '<ul class="pagination">';
    $next_page = $current_page + 1;
    $prev_page = $current_page - 1;

    $prev_class = "";
    $next_class = "";
    if ($prev_page < 0) {
        $prev_class = "disabled";
    }
    if ($next_page > ($total_pages - 1)) {
        $next_class = "disabled";
    }

    $markup .= "<li class='{$prev_class}'><a href='?{$pag_name}={$prev_page}#{$sel_name}'>&laquo;</a></li>";
    for ($i=0; $i<$total_pages; $i++) {
        if ($i == $current_page) {
            $class = "active";
        }
        else {
            $class = "";
        }
        $markup .= "<li class='{$class}'><a href='?{$pag_name}={$i}#{$sel_name}'>{$i}</a></li>";
    }
    $markup .= "<li class='{$next_class}'><a href='?{$pag_name}={$next_page}#{$sel_name}'>&raquo;</a></li>";
    $markup .= '</ul>';
    return $markup;
}

function user_link_table_length($current_user) {
    global $mysqli;
    $current_user = $mysqli->real_escape_string($current_user);
    $result = $mysqli->query("SELECT COUNT(`rurl`) FROM `redirinfo` WHERE `user` = '{$current_user}';") or showerror();

    $count = ($result->fetch_assoc());
    $count = (double) $count["COUNT(`rurl`)"];
    $pages = $count / 30;
    return ceil($pages);
}

if (!is_array($auth->islogged())) {
    echo "<h3>You must <a href='../login.php'>login</a> to access this page.</h3><br><a href='index.php'>Home</a>";
    require_once '../layout-footerlg.php';
    die();
} else {
    $userinfo = $auth->islogged();
    function fetchurls($lstart = 0, $limit = 30) {
        global $userinfo;
        global $mysqli;
        $sqr = "SELECT `baseval`,`rurl`,`date`,`lkey` FROM `redirinfo` WHERE user = '{$mysqli->real_escape_string($userinfo['username'])}' ORDER BY `rid` LIMIT {$lstart} , 30;";
        $res = $mysqli->query($sqr);
        $links =  mysqli_fetch_all($res, MYSQLI_ASSOC);

        $linkshtml = '<table class="table table-hover"><tr><th>Link ending</th><th>Long Link</th><th>Date</th><th>Secret</th></tr>';
        foreach ($links as $link) {
            $is_secret = "False";
            if (strlen($link['lkey']) > 1) {
                $is_secret = "True";
            }
            $linkshtml = $linkshtml . "<tr><td>" . $link['baseval'] . '</td>'
                    . "<td>" . substr($link['rurl'], 0, 255) . '</td>'
                    . "<td>" . $link['date'] . '</td>'
                    . "<td>" . $is_secret . "</td>";
        }
        $linkshtml = $linkshtml . "</tr></table>";
        return $linkshtml;
    }

    $links_total_pages = user_link_table_length($userinfo['username']);
    $linkshtml = false;
    if ($links_page && is_numeric($links_page)) {
        // If valid links page
        $links_page = intval($links_page);
        if ($links_page < $links_total_pages && $links_page > 0) {
            $ls = (30 * $links_page);
            $lim = 30;
            $linkshtml = fetchurls($ls, $lim);
        }
    }
    if ($linkshtml == false) {
        $linkshtml = fetchurls();
        $links_page = 0;
    }
    $linkshtml .= paginate_table($links_page, $links_total_pages, "lpage", "links");


    echo "<h3>Polr Dashboard</h3><br>";
    echo '<ul class="nav nav-tabs" id="tabsb">
            <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
            <li><a href="#links" data-toggle="tab">My links</a></li>
            <li><a href="#messages" data-toggle="tab">Messages</a></li>
            <li><a href="#settings" data-toggle="tab">Settings</a></li>';
    if ($isadmin == true) {
        echo '<li><a href="#adminpanel" data-toggle="tab">Admin Panel</a></li>';

        function fetchurlsadmin($lstart = 0, $limit = 30) {
            global $mysqli;
            $sqr = "SELECT `baseval`,`rurl`,`date`,`user`,`ip`,`lkey` FROM `redirinfo` ORDER BY `rid` LIMIT {$lstart} , 30;";
            $res = $mysqli->query($sqr);
            $links = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $linkshtml = '<table class="table table-hover"><tr><th>Link ending</th><th>Long Link</th><th>Date</th><th>Link Owner</th><th>IP</th><th>Secret</th><th>Disable/Enable</th></tr>';
            foreach ($links as $link) {
                $is_secret = "False";
                if (strlen($link['lkey']) > 1) {
                    $is_secret = "True";
                }


                $rurl_trimmed = substr($link['rurl'], 0, 200);
                if (strlen($link['rurl']) > 200) {
                    $rurl_trimmed .= "<h4 class='inline'><a href='../+{$link['baseval']}'>...</a></h4>";
                }
                $linkshtml = $linkshtml . "<tr><td>" . $link['baseval'] . '</td>'
                        . "<td>" . $rurl_trimmed . '</td>'
                        . "<td>" . $link['date'] . '</td>'
                        . "<td>" . $link['user'] . '</td>'
                        . "<td>" . $link['ip'] . '</td>'
                        . "<td>" . $is_secret . "</td>";
                if ($link['rurl'] == 'disabled') {
                    $linkshtml = $linkshtml . '<td><span class=' . $link['baseval'] . '><input type="button" value="Enable" onClick="doenable(\'' . $link['baseval'] . '\');" class="btn btn-sm btn-success enablelink" id="' . $link['baseval'] . '" />' . '</span></td></tr>';
                } else {
                    $linkshtml = $linkshtml . "<td>" . '<span class=' . $link['baseval'] . '><input type="button" value="Disable" onClick="dodisable(\'' . $link['baseval'] . '\');" class="btn btn-sm btn-danger disablelink" id="' . $link['baseval'] . '" />' . '</span></td></tr>';
                }
            }
            $linkshtml = $linkshtml . "</tr></table>";
            return $linkshtml;
        }

        function fetchusersadmin($lstart = 0, $limit = 30) {
            global $mysqli;
            $sqr = "SELECT `username`,`email`,`valid` FROM `auth` ORDER BY `uid` LIMIT {$lstart} , 30;";
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

        $users_total_pages = table_length("auth");
        $usersadmin = false;
        if ($users_page && is_numeric($users_page)) {
            // If valid users page
            $users_page = intval($users_page);
            if ($users_page < $users_total_pages  && $users_page > 0) {
                $ls1 = (30 * $users_page);
                $lim1 = 30;

                $usersadmin = fetchusersadmin($ls1, $lim1);
            }
        }

        if ($usersadmin == false) {
            $usersadmin = fetchusersadmin();
            $users_page = 0;
        }
        $usersadmin .= paginate_table($users_page, $users_total_pages, "upage", "adminpanel");


        $admin_links_total_pages = table_length("redirinfo");

        $linksadmin = false;
        if ($admin_links_page && is_numeric($admin_links_page)) {
            // If valid users page
            $users_page = intval($admin_links_page);
            if ($admin_links_page < $admin_links_total_pages && $admin_links_page > 0) {
                $ls2 = (30 * $users_page);
                $lim2 = 30;
                $linksadmin = fetchurlsadmin($ls2, $lim2);
            }
        }

        if ($linksadmin == false) {
            $linksadmin = fetchurlsadmin();
            $admin_links_page = 0;
        }

        $linksadmin .= paginate_table($admin_links_page, $admin_links_total_pages, "alpage", "adminpanel");

        $status = "";// file_get_contents('https://raw.githubusercontent.com/Cydrobolt/polr/notices/anotices'); // fetch notices from Github
        if (strlen($status)<30) {
            $msges = '<div class="tab-pane" id="messages"><br><b>There are <span style="color:green">no new messages</span>.</b></div>';
        }
        else {
            $msges = '<div class="tab-pane" id="messages"><br>'.$status.'</div>';
        }
    }
    else {
        // Shown to users
        $status = "";// file_get_contents('https://raw.githubusercontent.com/Cydrobolt/polr/notices/unotices'); // fetch notices from Github
        if (strlen($status)<30) {
            $msges = '<div class="tab-pane" id="messages"><br><b>There are <span style="color:green">no new messages</span>.</b></div>';
        }
        else {
            $msges = '<div class="tab-pane" id="messages"><br>'.$status.'</div>';
        }
    }

    echo '</ul>';
    echo '<div class="tab-content">
              <div class="tab-pane active" id="home"><br><h2>Welcome to '.$wsn.'\'s Polr dashboard.</div>
              <div class="tab-pane" id="links"><br>' . $linkshtml . '</div>

              '.$msges.'
              <div class="tab-pane" id="settings"><br>
              <h3>Change password</h3>
              <form action=\'ucp-settingsp.php\' method=\'POST\'>
                  <input type=\'hidden\' name=\'action\' value=\'changepw\' />
                  Old Password: <input type=\'password\' name=\'currpw\' />
                  New Password: <input type=\'password\' name=\'newpw\' />
                  <input type=\'submit\' class=\'btn btn-success\'/>
              </form>
          </div>';
    if ($isadmin == true) {
        echo '<div class="tab-pane" id="adminpanel"><br />';
        echo '<p>Polr Links:</p>' . $linksadmin . '<br><p>Polr Users</p>' . $usersadmin. '<script src="../js/ucp.js"></script>';
        echo '<p>Disable a Link</p>';
        echo '<input type="text" id="linkAction" placeholder="Link ending" style="width:30%;" class="form-control" />';
        echo '<div class="linkActionBtn"><a href="javascript:void()" onclick="customDisableLink();" class="btn btn-sm btn-danger">Disable</a>&nbsp;';
        echo '<a href="javascript:void()" onclick="customEnableLink();" class="btn btn-sm btn-success">Enable</a></div>';
        if ($debug == 1) {
            '<br>Debug Variables: <br>Default IP Fetch: ' . $ip . '<br>X-Forwarded-For:' . @$headers['X-Forwarded-For'] . '<br>Forwarded-For' . @$headers['forwarded-for'];
        }
    }


    echo '</div>';
}
