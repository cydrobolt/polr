<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class Controller extends BaseController {
    protected function currIsAdmin() {
        $role = session('role');
        if ($role == 'admin') {
            return true;
        }
        else {
            return false;
        }
    }

    protected function isLoggedIn() {
        $username = session('username');
        if (!isset($username)) {
            return false;
        }
        else {
            return true;
        }
    }

    protected static function checkRequiredArgs($required_args=[]) {
        array_push($required_args, NULL);

        if (count(array_unique($required_args)) < count($required_args)) {
            return false;
        }
        else {
            return true;
        }

    }
}
