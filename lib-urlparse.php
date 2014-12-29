<?php

class parseurl {

    public static function urlencode($url, $encode_special) {
        $out = array();
        $len = strlen($url);
        for ($i = 0; $i < $len; $i++) {
            $c = $url[$i];
            $ascii = ord($c);
            if ($ascii <= 32 || $ascii >= 127) {
                $out[] = rawurlencode($c);
            } else if ($encode_special && ($ascii == 35 || $ascii == 37)) {
                $out[] = rawurlencode($c);
            } else {
                $out[] = $c;
            }
        }
        return implode('', $out);
    }

    public static function escape($s) {
        $unquoted = rawurldecode($s);
        while ($unquoted != $s) {
            $s = $unquoted;
            $unquoted = rawurldecode($s);
        }
        $s = self::urlencode($s, TRUE);

        return $s;
    }

    /**
     * Canonicalizes a full URL according to Google's definition.
     *
     * @param string $url
     * @return a string array of canonicalized URL parts
     */
    public static function getCanonicalizedUrl($url) {
        $canurl = self::canonicalize($url);
        return $canurl['canonical'];
    }

    /**
     * Canonicalizes a full URL according to Google's definition.
     *
     * @param string $url
     * @return a string array of canonicalized URL parts
     */
    public static function canonicalize($url) {

        $finalurl = $url;

        // Strip off fragment
        $pos = strpos($url, '#');
        if ($pos !== FALSE) {
            $finalurl = substr($url, 0, $pos);
        }

        // Strip off leading and trailing white space
        $finalurl = trim($finalurl);

        // Remove line feeds, return carriages, tabs, vertical tabs
        $finalurl = str_replace(array("\x09", "\x0A", "\x0D", "\x0B"), '', $finalurl);

        $finalurl = self::escape($finalurl);

        // Schemeless urls become HTTP
        if (! preg_match("/^[a-zA-Z]+:\/\//", $finalurl)) {
            $finalurl = 'http://' . $finalurl;
        }

        // Now extract hostname & path
        // parse_url is noisy prior to php 5.3.3.  Need to silence with '@'
        $parts = @parse_url($finalurl);

        $hostname = self::escape($parts['host']);

        // Deal with hostname first
        // Replace all leading and trailing dots
        $hostname = trim($hostname, '.');

        // Replace all consecutive dots with one dot
        $hostname = preg_replace('/\.{2,}/', '.', $hostname);

        // Make it lowercase
        $hostname = strtolower($hostname);

        if (is_numeric($hostname)) {
            // weird case where hostname is one integer.
            //  some browsers (chrome)  actually accept this!
            $hostnameip = ip2long(long2ip($hostname));
        } else {
            // See if its a valid IP
            $hostnameip = ip2long($hostname);
        }

        if ($hostnameip === FALSE) {
            $is_ip = false;
        } else {
            $is_ip = true;
            $hostname = long2ip($hostnameip);
        }

        if (!isset($parts['path'])) {
            $path = '/';
        } else {
            $path = self::escape($parts['path']);
        }

        $pathparts = explode('/', $path);
        foreach ($pathparts as $key => $value) {
            if ($value == '..') {
                if ($key != 0) {
                    unset($pathparts[$key - 1]);
                    unset($pathparts[$key]);
                } else {
                    unset($pathparts[$key]);
                }
            } elseif ($value == '.' || empty($value)) {
                unset($pathparts[$key]);
            }
        }
        if (substr($path, -1, 1) == '/') {
            $append = '/';
        } else {
            $append = '';
        }

        $path = '/' . implode('/', $pathparts);
        if ($append && substr($path, -1, 1) != '/') {
            $path .= $append;
        }

        $canurl = $parts['scheme'] . '://';
        if (!empty($parts['userinfo'])) {
            $realurl .= $parts['userinfo'] . '@';
        }
        $canurl .= $hostname;
        if (!empty($parts['port']) &&
            (($parts['scheme'] == 'http' && $parts['port'] != 80) ||
             ($parts['scheme'] == 'https' && $parts['port'] != 443))) {
                $canurl .= ':' . $parts['port'];
        }
        $canurl .= $path;

        if (isset($parts['query'])) {
            $query =  $parts['query'];
            $canurl .= '?' . $query;
        } else if ($finalurl[strlen($finalurl)-1] == '?') {
            $query = '';
            $canurl .= '?';
        } else {
            $query = null;
        }

        return array(
            'canonical' => $canurl,
            'original'  => $url,
            'host'      => $hostname,
            'path'      => $path,
            'query'     => $query,
            'is_ip'     => $is_ip
        );
    }

    /**
     * Hash up a list of values from makePrefixes() (will possibly be
     * combined into that function at a later date
     *
     * @param array() $prefixarray
     * @return Ambigous <multitype:, multitype:string unknown >
     */
    static function makeHashes($prefixarray) {
        $returnprefixes = array();
        foreach ($prefixarray as $value) {
            $fullhash = self::sha256($value);
            $returnprefixes[$fullhash] = array(
                'original' => $value,
                'prefix'   => substr($fullhash, 0, 8),
                'hash'     => $fullhash);
        }
        return $returnprefixes;
    }

    /**
     * construct URL paths given the query parameters
     *
     * @param string $path
     * @param string $query
     * @return multitype: string
     */
    static function makePaths($path, $query) {
        $p = array();
        if (!is_null($query)) {
            array_push($p, $path . '?' . $query);
        }
        array_push($p, $path);

        if ($path == '/') {
            return $p;
        }

        array_push($p, '/');
        $parts = explode('/', $path);
        $len = count($parts) - 1;

        // handle case where path ends in a '/' already
        if (empty($parts[$len])) {
            $len -= 1;
        }

        // no more than 3 of these (we already have '/' already, so 4 total)
        $len = min($len, 3);

        for ($i = 1; $i < $len; $i++) {
            array_push($p, '/' . implode('/', array_slice($parts, 1, $i)) . '/');
        }
        return $p;
    }

    /**
     * Construct host prefixes given the host name, URL path, and
     * query strings.
     *
     * @param string $host
     * @param string $path
     * @param string $query
     * @param boolean $usingip
     * @return multitype:
     */
    static function makePrefixes($host, $path, $query, $usingip) {
        $out = array();
        $hosts = self::makeHosts($host, $usingip);
        $paths = self::makePaths($path, $query);
        foreach ($hosts as $host) {
            foreach ($paths as $j => $p) {
                array_push($out, $host . $p);
            }
        }
        return $out;
    }

    /**
     * Make URL prefixes for use after a hostkey check
     *
     * @param string $host
     * @param string $path
     * @param string $query
     * @param boolean $usingip
     * @return multitype:string
     */
    static function makePrefixesHashes($host, $path, $query, $usingip) {
        $prefixes = self::makePrefixes($host, $path, $query, $usingip);
        return self::makeHashes($prefixes);
    }

    /**
     *  Makes the host keys for initial lookup
     *
     *  maps 1.2.3.4 => ( 1.2.3.4 ) (ip address)
     *           b.a => ( b.a )
     *         c.b.a => ( c.b.a, b.a )
     *       d.c.b.a => ( c.b.a, b.a )  (only 2 dots)
     *
     */
    static function makeHostList($host, $usingip) {
        if ($usingip) {
            return array($host);
        } else {
            $hostparts = explode('.', $host);
            $len = count($hostparts);
            if ($len <= 2) {
                return array($host);
            } else {
                return array(implode('.', array_slice($hostparts, $len - 3)),
                    implode('.', array_slice($hostparts, $len - 2)));
            }
        }
    }

    /**
     *
     * Maps IPADDR -> IPADDR (identity)
     *
     */
    static function makeHosts($host, $usingip) {
        // always use the full host.
        $hosts = array($host);
        if (!$usingip) {
            $hostparts = explode('.', $host);
            // TRICKY... make sure domain has at least one dot, and no
            // more than 4.
            $len = count($hostparts) - 1;
            for ($i = max(1, $len - 4); $i < $len; ++$i) {
                array_push($hosts, implode('.', array_slice($hostparts, $i)));
            }
        }
        return $hosts;
    }

    /**
     * Make Hostkeys for use in a full URL lookup
     *
     * @param string $host
     * @param boolean $usingip
     * @return multitype:string
     */
    static function makeHostKeyList($host, $usingip) {
        // turn 'www.google.com' into ('www.google.com', 'google.com')
        $hosts = self::makeHostList($host, $usingip);

        // Now make key & key prefix
        $returnhosts = array();
        foreach ($hosts as $host) {
            $host = $host . '/';
            $fullhash = self::sha256($host);
            $returnhosts[] = array(
                'host'     => $host,
                'host_key' => substr($fullhash, 0, 8),
                'hash'     => $fullhash
            );
        }
        return $returnhosts;
    }

    /**
     * SHA-256 input
     *
     * @param string $data
     * @return hex-encoded sha256 string
     */
    static function sha256($data) {
        return hash('sha256', $data);
    }
}
