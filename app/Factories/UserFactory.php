<?php
namespace App\Factories;

use Hash;
use App\Models\User;
use App\Helpers\CryptoHelper;

class UserFactory {    
    public static function createUser($username, $email, $password, $active=0, $ip='127.0.0.1') {
        $hashed_password = Hash::make($password);

        $recovery_key = CryptoHelper::generateRandomHex(50);
        $user = new User;
        $user->username = $username;
        $user->password = $hashed_password;
        $user->email = $email;
        $user->recovery_key = $recovery_key;
        $user->active = $active;
        $user->ip = $ip;
        $user->save();

        return $user;
    }

}
