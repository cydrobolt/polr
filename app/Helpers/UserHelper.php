<?php
namespace App\Helpers;
use App\Models\User;
use Hash;

class UserHelper {
    public static function userExists($username) {
        $user = User::where('active', 1)
            ->where('username', $username)
            ->first();

        return ($user ? true : false);
    }

    public static function emailExists($email) {
        $user = User::where('active', 1)
            ->where('email', $email)
            ->first();

        return ($user ? true : false);
    }

    public static function validateUsername($username) {
        return ctype_alnum($username);
    }

    public static function validateEmail($email) {
        // TODO validate email here
        return true;
    }

    public static function checkCredentials($username, $password) {
        $hashed_password = Hash::make($password);

        $user = User::where('active', 1)
            ->where('username', $username)
            ->where('password', $hashed_password)
            ->first();

        if ($user == null) {
            return false;
        }
        else {
            return true;
        }
    }
}
