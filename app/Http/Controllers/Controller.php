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
}
