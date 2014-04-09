<?php
//API Documentation: https://github.com/Cydrobolt/polr/wiki/Polr-PaaS-API-Documentation

/* How to use this library:
* Create a new Polr object : $polr = new polr(apikeyhere);
* To shorten: $polr->shorten(url);
* To lookup: $polr->lookup(url);
*/

public class polr($polrkey) {
	$apikey = $polrkey;

	function buildlink($apikey,$action,$url) {
		$url = "http://polr.cf/api.php?apikey=".$apikey."&action=".$action."&url=."$url;
		return $url;
	}
	public function shorten($url) {
		global $apikey;
		$query2api = $this->buildlink($polrkey,"shorten",$url);
		$shortened = file_get_contents($query2api);
		return $shortened;
	}
	public function lookup($url) {
		global $apikey;
		$query2api = $this->buildlink($polrkey,"lookup",$url);
		$lookedup = file_get_contents($query2api);
		return $lookedup;
	}
}
