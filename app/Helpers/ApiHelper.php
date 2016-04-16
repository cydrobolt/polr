<?php
namespace App\Helpers;
use App\Models\Link;
use App\Helpers\UserHelper;

class ApiHelper {
    public static function checkUserApiQuota($username) {
        /**
         *
         * @return boolean; whether API quota is met
         */

        $last_minute_unix = time() - 60;
        $last_minute = new \DateTime();
        $last_minute->setTimestamp($last_minute_unix);

        $user = UserHelper::getUserByUsername($username);

        $links_last_minute = Link::where('is_api', 1)
            ->where('creator', $username)
            ->where('created_at', '>=', $last_minute)
            ->count();

        return ($user->api_quota > -1 && $links_last_minute >= $user->api_quota);
    }
}
