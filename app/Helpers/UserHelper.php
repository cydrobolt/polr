<?php
namespace App\Helpers;

use App\Models\User;
use App\Helpers\CryptoHelper;
use Hash;

class UserHelper {
    public static function userExists($username) {
        /* XXX: used primarily with test cases */

        $user = self::getUserByUsername($username, $inactive=true);

        return ($user ? true : false);
    }

    public static function emailExists($email) {
        /* XXX: used primarily with test cases */

        $user = self::getUserByEmail($email, $inactive=true);

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

    public static function resetRecoveryKey($username) {
        $recovery_key = CryptoHelper::generateRandomHex(50);

        $user = self::getUserByUsername($username);

        if (!$user) {
            return false;
        }

        $user->recovery_key = $recovery_key;
        $user->save();

        return true;
    }

    public static function getUserById($user_id) {
        $user = User::where('id', $user_id)
            ->where('active', 1)
            ->first();
        return $user;
    }

    public static function getUserByUsername($username, $inactive=false) {
        $user = User::where('username', $username)
            ->where('active', (!$inactive))
            ->first();
        return $user;
    }

    public static function getUserByEmail($email, $inactive=false) {
        $user = User::where('email', $email)
            ->where('active', (!$inactive))
            ->first();
        return $user;
    }

}
