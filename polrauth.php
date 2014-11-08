<?php

require_once('req.php'); //load config etc
require_once('password.php');

class polrauth {

    public $authcreds = [];

    public function islogged () {
        if (@$_SESSION['li'] !== sha1('li')) {
            return false;
        } else {
            $data['username'] = $_SESSION['username'];
            $data['role'] = $_SESSION['role'];
            return $data;
        }
    }
    public function prepare ($query) {
        global $mysqli;
        global $debug;
        if(!($p = $mysqli->prepare($query))) {
            if ($debug === 1) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            else {
                showerror();
                return false;

            }
        }
        return $p;
    }
    public function gr ($p) {
        global $debug;
        // Fetches result from prepared statement
        if(!($g = $p->get_result())) {
            if ($debug === 1) {
                echo "Prepare failed: (" . $p->errno . ") " . $p->error;
            }
            showerror();
            return false;

        }
        return $g;
    }

    public function isadminli() {
        if ($_SESSION['li'] !== sha1('li')) {
            return false;
        } else {
            if ($_SESSION['role'] == 'adm') {
                return true;
            } else {
                return false;
            }
        }
    }

    public function isadmin ($user) {
        if ($this->getrole($user) == 'adm') {
            return true;
        } else {
            return false;
        }
    }

    public function headblock () {
        if (is_array($this->islogged())) {
            echo "<!--";
        }
    }

    public function headendblock ($ar = false) {
        if (is_array($this->islogged())) {
            $authinfo = $this->islogged();
            echo "-->";
            $text = '<div class=\'nav pull-right navbar-nav\' style=\'color: white\'>
        <li class=\'dropdown\'>
        <a class="dropdown-toggle" href="#" data-toggle="dropdown" style=\'padding-right: 10px\'>' . $authinfo['username'] . ' <strong class="caret"></strong></a>

            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
                <li><a tabindex="-1" href="admin">Dashboard</a></li>
                <li><a tabindex="-1" href="admin">Settings</a></li>
                <li><a tabindex="-1" href="logout.php">Logout</a></li>
            </ul>
        </li>
        </div>';
            if ($ar == true) {
                # if called from UCP
                $text = '<div class=\'nav pull-right navbar-nav\' style=\'color: white\'>
        <li class=\'dropdown\'>
        <a class="dropdown-toggle" href="#" data-toggle="dropdown" style=\'padding-right: 10px\'>' . $authinfo['username'] . ' <strong class="caret"></strong></a>

            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
                <li><a tabindex="-1" href="index.php">Dashboard</a></li>
                <li><a tabindex="-1" href="index.php">Settings</a></li>
                <li><a tabindex="-1" href="../logout.php">Logout</a></li>
            </ul>
        </li>
        </div>';
            }


            echo $text;
        }
    }

    public function processlogin ($username, $password) {
        global $mysqli;
        $a = "SELECT `password`,`valid` FROM `auth` WHERE `username`=?";
        $b = $mysqli->prepare($a);
        $b->bind_param('s', $username);
        $b->execute();
        $d = $b->get_result();
        echo $b->error;
        $c = mysqli_fetch_assoc($d);
        $hpw = $c['password'];
        $uv = $c['valid'];



        if ((!$hpw) || (!$uv)) {
            return false;
        }

        $pwf = password_verify($password, $hpw);
        if ($pwf && ($uv = 1)) {
            return true;
        } else {
            return false;
        }
    }

    public function getrole ($username) {
        global $mysqli;
        $a = "SELECT role FROM auth WHERE username='{$username}'";
        $b = $mysqli->query($a) or showerror();
        $c = mysqli_fetch_assoc($b);
        return $c['role'];
    }

    public function getinfomu ($username) {
        global $mysqli;
        $username = $mysqli->real_escape_string($username);
        $a = "SELECT `role`,`username`,`ip`,`theme`,`rkey` FROM `auth` WHERE username='{$username}';";
        $b = $mysqli->query($a) or showerror();

        $numrows = $b->num_rows;
        if (!$numrows) {
            return false;
        }
        $c = mysqli_fetch_assoc($b);
        return $c;
    }

    public function getinfome ($email) {
        global $mysqli;
        $email = $mysqli->real_escape_string($email);
        //$a = "SELECT `role`,`username`,`ip,`theme`,`rkey` FROM `auth` WHERE email='{$email}';";
        $a = "SELECT `role`,`username`,`ip`,`theme`,`rkey` FROM `auth` WHERE email='{$email}';";
        $b = $mysqli->query($a) or showerror();

        $numrows = $b->num_rows;
        if (!$numrows) {
            return false;
        }
        $c = mysqli_fetch_assoc($b);
        return $c;
    }

    public function remember_me () {
        // Extend the login session cookie to last 30 days
        $params = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    public function crkey ($username) {
        global $mysqli;
        $nrkey = sha1($username . (string) (rand(100, 4434555)) . date('yDm'));
        $usernamesan = $mysqli->real_escape_string($username);
        $qr = "UPDATE auth SET rkey='{$nrkey}' WHERE username='$usernamesan';";
        $e = $mysqli->query($qr) or showerror();
    }

}
