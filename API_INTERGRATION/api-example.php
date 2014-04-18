<?
//PolrLib Example

include('polrlib.php');

$polr = new polr("insert api key here");

$toshorten = "http://google.com";

$shortened = $polr->shorten($toshorten);

$tolookup = "1";

$lookedup = $polr->lookup($tolookup);

echo $toshorten."<br>".$tolookup."<br>".$shortened."<br>".$lookedup;
