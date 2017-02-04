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
         * return the link object, or false.
         * @return Link model instance
         */

        $link = Link::where('short_url', $link_ending)
            ->first();

        if ($link != null) {
            return $link;
        }
        else {
            return false;
        }
    }

    static public function longLinkExists($long_url) {
        /**
         * Provided a long link (string),
         * check whether the link is in the DB.
         * @return boolean
         */
        $link = Link::longUrl($long_url)
            ->where('is_custom', 0)
            ->where('secret_key', '')
            ->first();

        if ($link == null) {
            return false;
        }
        else {
            return $link->short_url;
        }
    }

    static public function validateEnding($link_ending) {
        $is_valid_ending = preg_match('/^[a-zA-Z0-9-_]+$/', $link_ending);
        return $is_valid_ending;
    }

    static public function findPseudoRandomEnding() {
        /**
         * Return an available pseudorandom string of length _PSEUDO_RANDOM_KEY_LENGTH,
         * as defined in .env
         * Edit _PSEUDO_RANDOM_KEY_LENGTH in .env if you wish to increase the length
         * of the pseudorandom string generated.
         * @return string
         */

        $pr_str = '';
        $in_use = true;

        while ($in_use) {
            // Generate a new string until the ending is not in use
            $pr_str = str_random(env('_PSEUDO_RANDOM_KEY_LENGTH'));
            $in_use = LinkHelper::linkExists($pr_str);
        }

        return $pr_str;
    }

    static public function findSuitableEnding() {
        /**
         * Provided an in-use link ending (string),
         * find the next available base-32/62 ending.
         * @return string
         */
        $base = env('POLR_BASE');

        $link = Link::where('is_custom', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($link == null) {
            $base10_val = 0;
            $base_x_val = 0;
        }
        else {
            $latest_link_ending = $link->short_url;
            $base10_val = BaseHelper::toBase10($latest_link_ending, $base);
            $base10_val++;
        }


        $base_x_val = null;

        while (LinkHelper::linkExists($base_x_val) || $base_x_val == null) {
            $base_x_val = BaseHelper::toBase($base10_val, $base);
            $base10_val++;
        }

        return $base_x_val;
    }
}
