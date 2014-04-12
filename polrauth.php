<?php

require_once('req.php'); //load config etc
require_once('password.php');

class polrauth {

    public $authcreds = [];

    public function islogged() {
        if ($_SESSION['li'] !== sha1('li')) {
            return false;
        } else {
            $data['username'] = $_SESSION['username'];
            $data['role'] = $_SESSION['role'];
            return $data;
        }
    }
    
    public function isadminli() {
        if ($_SESSION['li'] !== sha1('li')) {
            return false;
        }
        else {
            if($_SESSION['role'] == 'adm') {
                return true;
            }
            else {
                return false;
            }
        }
    }
    public function isadmin($user) {
        if($this->getrole($user)=='adm') {
            return true;
        }
        else {
            return false;
        }
    }
    public function headblock() {
        if (is_array($this->islogged())) {
            echo "<!--";
        }
    }

    public function headendblock() {
        if (is_array($this->islogged())) {
            $authinfo = $this->islogged();
            echo "-->";
            $text = '<a href="ucp.php" class="btn btn-success btn-default"><span class="glyphicon glyphicon-off"></span> Logged in as ' . $authinfo['username'] . '</a><a href="logout.php" class="btn btn-success btn-default">Logout</a>';
            echo $text;
        }
    }

    public function processlogin($username, $password) {
        global $mysqli;
        $a = "SELECT password FROM auth WHERE username='{$username}'";
        $b = $mysqli->query($a) or showerror();
        $c = mysqli_fetch_assoc($b);
        $hpw = $c['password'];
        $a = "SELECT valid FROM auth WHERE username='{$username}'";
        $b = $mysqli->query($a) or showerror();
        $c = mysqli_fetch_assoc($b);
        $uv = $c['valid'];
        if ((!$hpw)||(!$uv)) {
            return false;
        }

        $pwf = password_verify($password, $hpw);
        if ($pwf&&($uv=1)) {
            return true;
        } else {
            return false;
        }
    }

    public function getrole($username) {
        global $mysqli;
        $a = "SELECT role FROM auth WHERE username='{$username}'";
        $b = $mysqli->query($a) or showerror();
        $c = mysqli_fetch_assoc($b);
        return $c['role'];
    }

}
