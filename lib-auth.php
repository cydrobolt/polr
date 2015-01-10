<?php
/*
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
*/
require_once('lib-core.php');
require_once('lib-password.php');

class polrauth {

    public $authcreds = array();

    public function islogged () {
        if (@$_SESSION['li'] !== sha1('li')) {
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
                                <li><a tabindex="-1" href="admin/index.php">Dashboard</a></li>
                                <li><a tabindex="-1" href="admin/index.php">Settings</a></li>
                                <li><a tabindex="-1" href="admin/logout.php">Logout</a></li>
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
                                    <li><a tabindex="-1" href="logout.php">Logout</a></li>
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
