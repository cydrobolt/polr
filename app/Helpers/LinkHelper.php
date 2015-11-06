<?php
namespace App\Helpers;
use App\Models\Link;
use App\Helpers\BaseHelper;

class LinkHelper {
    static public function checkIfAlreadyShortened($long_link) {
        /**
         * Provided a long link (string),
         * detect whether the link belongs to an URL shortener.
         * @return boolean
         */
        $shortener_domains = [
            'polr.me',
            'bit.ly',
            'is.gd',
            'tiny.cc',
            'adf.ly',
            'ur1.ca',
            'goo.gl',
            'ow.ly',
            'j.mp',
            't.co',
            env('APP_ADDRESS')
        ];

        foreach ($shortener_domains as $shortener_domain) {
            $url_segment = ('://' . $shortener_domain);
            if (strstr($long_link, $url_segment)) {
                return true;
            }
        }
        return false;
    }

    static public function linkExists($link_ending) {
        /**
         * Provided a link ending (string),
         * check whether the ending is in use.
         * @return boolean
         */
        $link = Link::where('short_url', $link_ending)
            ->first();
        if ($link == null) {
            return false;
        }
        else {
            return true;
        }
    }

    static public function findSuitableEnding() {
        /**
         * Provided an in-use link ending (string),
         * find the next available base-32/62 ending.
         * @return string
         */
        $base = env('POLR_BASE');

        $link = Link::where('is_custom', '0')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($link == null) {
            $latest_link_ending = "1";
        }
        else {
            $latest_link_ending = $link->short_url;
        }

        $base10_val = BaseHelper::toBase10($latest_link_ending, $base);
        $base10_val++;
        $base_x_val = null;

        while (LinkHelper::linkExists($base_x_val) || $base_x_val == null) {
            $base_x_val = BaseHelper::toBase($base10_val, $base);
            $base10_val++;
        }

        return $base_x_val;
    }
}
