<?php
namespace App\Helpers;

class CryptoHelper {
    public static function generateRandomHex($rand_bytes_num) {
        $rand_bytes = openssl_random_pseudo_bytes($rand_bytes_num, $crypt_secure);
        return bin2hex($rand_bytes);
    }
}
