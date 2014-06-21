<?php

require_once 'req.php';

class sgmail {

    public function sendmail($to, $subject, $message) {
        mail($to,$subject,$message); // feel free to edit, if you use services such as SendGrid
    }

}
