<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiHelper;

class ApiHelper {
    public static function checkUserApiQuota($username) {
        // pass
        return true;
    }
}
