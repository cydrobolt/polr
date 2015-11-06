<?php
namespace App\Helpers;

// http://stackoverflow.com/questions/4964197/converting-a-number-base-10-to-base-62-a-za-z0-9/4964352#4964352
class BaseHelper {
    public static function toBase($num, $b=62) {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $r = $num  % $b;
        $res = $base[$r];
        $q = floor($num/$b);
        while ($q) {
            $r = $q % $b;
            $q = floor($q/$b);
            $res = $base[$r] . $res;
        }
        return $res;
    }

    public static function toBase10($num, $b=62) {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $limit = strlen($num);
        $res = strpos($base,$num[0]);
        for ($i=1; $i<$limit; $i++) {
            $res = $b * $res + strpos($base, $num[$i]);
        }
        return $res;
    }
}
