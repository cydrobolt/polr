<?php
namespace App\Helpers;
use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;

class ClickHelper {

    static private function getHost($url) {
        // Return host given URL; NULL if host is
        // not found.
        return parse_url($url, PHP_URL_HOST);
    }

    static public function recordClick(Link $link, Request $request) {
        /**
         * Given a Link model instance and Request object, process post click operations.
         * @param Link model instance $link
         * @return boolean
         */

        $location = geoip()->getLocation(); // Withour the IP specified, it will be detected automatically and use the real user IP if website is behind CloudFlare
        $referer = $request->server('HTTP_REFERER');

        $click = new Click;
        $click->link_id = $link->id;
        $click->ip = $location->ip;
        $click->country = $location->iso_code;
        $click->referer = $referer;
        $click->referer_host = ClickHelper::getHost($referer);
        $click->user_agent = $request->server('HTTP_USER_AGENT');
        $click->save();

        return true;
    }
}
