<?php
namespace App\Helpers;
use App\Models\User;
use Hash;

class UserHelper {
    public static function userExists($username) {
        $user = User::where('username', $username)
            // ->where('active', 1)
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
        $user = User::where('active', 1)
            ->where('username', $username)
            ->first();

        if ($user == null) {
            return false;
        }

        $correct_password = Hash::check($password, $user->password);

        if (!$correct_password) {
            return false;
        }
        else {
            return ['username' => $username, 'role' => $user->role];
        }
    }

    public static function createNewRecoveryKey($username) {
        $recovery_key = CryptoHelper::generateRandomHex(50);

        $user = User::where('active', 1)
            ->where('username', $username)
            ->first();

        if ($user == null) {
            return false;
        }

        $user->recovery_key = $recovery_key;
        $user->save();
    }

    public static function getUserById($user_id) {
        $user = User::where('id', $user_id)
            ->where('active', 1)
            ->first();
        return $user;
    }

    public static function getUserByUsername($username) {
        $user = User::where('username', $username)
            ->where('active', 1)
            ->first();
        return $user;
    }

}
