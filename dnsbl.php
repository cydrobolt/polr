<?php
//I know this is named "dnsbl.php", but this is not a DNSBL module. It checks against
//Google SafeBrowsing databases. It used to be a DNSBL script, but it was later
//changed, and I didn't want to completetly refactor all the uses of this script.
//@project polrcore

require_once('parseurl.php');
class dnsbl {
    public function isbl( $url ) {
        $parseurl = new parseurl();
        $parsed = $parseurl->getCanonicalizedUrl($url);
        $url = strtolower($url);
        
        $apikey = "##HIDDEN - STOP PEEKING! ##";
        $safebrowsing = "https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey=".$apikey."&appver=1.5.2&pver=3.0&url=".$parsed;
        $response = file_get_contents($safebrowsing);
        if(strstr($response,'malware')) {
            return 'malware';
        }
        elseif (strstr($response,'phish')) {
            return 'phishing';
        }
        else {
            return false;
        }
}
}
