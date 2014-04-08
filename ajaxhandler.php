<?php
require_once('req.php');
class ajaxhandler {
    public function __construct() {
        global $mysqli;
    }
    function linkcheck($link) {
        global $mysqli;
        if(!ctype_alnum($link) || strlen($link)>20) {
            return 3;
        }
        $iscustom = "yes";
        $query = "SELECT rid FROM redirinfo WHERE baseval='{$link}'"; //check if baseval used already
        $result = $mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        $custom_existing = $row['rid'];
        
        if($custom_existing) {
            return false;
        }
        else {
            return true;
        }
        
    }
    
}
