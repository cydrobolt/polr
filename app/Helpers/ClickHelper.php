<?php

namespace App\Helpers;

use MaxMind\Db\Reader;

class ClickHelper
{
    /**
     * @param string $url
     * @return string|null
     */
    static public function getHost($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        return $host ? preg_replace('/^www./','', $host) : null;
    }

    /**
     * @param $ip
     * @return array
     */
    static public function getIpInfo($ip)
    {
        $databaseFile = __DIR__.'/../../storage/app/GeoLite2-Country.mmdb';
        $reader = new Reader($databaseFile);

        $data = $reader->get($ip);
        $reader->close();

        return $data;
    }

    /**
     * @param string $ip
     * @return string
     */
    static public function getCountry($ip)
    {
        $info = self::getIpInfo($ip);

        if (empty($info['country'])) {
            return 'XX';
        }

        return $info['country']['iso_code'];
    }
}
