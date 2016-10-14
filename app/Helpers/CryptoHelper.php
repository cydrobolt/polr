<?php
namespace App\Helpers;

class CryptoHelper {
    public static function generateRandomHex($rand_bytes_num) {
        $rand_bytes = random_bytes($rand_bytes_num);
        return bin2hex($rand_bytes);
    }
}
