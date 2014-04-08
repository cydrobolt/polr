<?php

require_once 'req.php';
require_once 'sglib/sendgrid-php.php';

class sgmail {

    public function sendmail($to, $subject, $message) {
        $url = 'https://sendgrid.com/';
        $user = '##HIDDEN - STOP PEEKING! ##';
        $pass = '##HIDDEN - STOP PEEKING! ##';

        $p = array(
            'api_user' => $user,
            'api_key' => $pass,
            'to' => $to,
            'subject' => $subject,
            'html' => $message,
            'from' => '##HIDDEN - STOP PEEKING! ##',
        );
        $sendgrid = new SendGrid($p['api_user'], $p['api_key']);
        $mail = new SendGrid\Email();
        $mail->addTo($p['to'])->
                setFrom($p['from'])->
                setSubject($p['subject'])->
                setText(strip_tags($p['html']))->
                setHtml($p['html']);
        $sendgrid->send($mail);
    }

}
