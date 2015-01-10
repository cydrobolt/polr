<?php

require_once 'lib-core.php';
require 'vendor/PHPMailer/PHPMailerAutoload.php';

/*
 * Polr Mailer Script
*/

class sgmail {

    public function sendmail($to, $subject, $message) {
		global $wsn;
		global $debug;
        try {
            global $smtpCfg;
            $smtpHost = $smtpCfg['servers'];
            $smtpFrom = $smtpCfg['from'];
            $smtpUsername = $smtpCfg['username'];
            $smtpPassword = $smtpCfg['password'];
        } catch (Exception $e) {
            echo "Email not properly configured. Contact the site owner to report this problem. <br />";
            showerror();
            return false;
        }


        // mail($to,$subject,$message);
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $smtpHost;  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $smtpUsername;                 // SMTP username
		$mail->Password = $smtpPassword;                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->From = $smtpFrom;
		$mail->FromName = $wsn;
		$mail->addAddress($to);     						  // Add a recipient

		/*
		$mail->addAddress('ellen@example.com');
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');						  // Optional recipients.
		$mail->addBCC('bcc@example.com');
		*/

		$mail->WordWrap = 80;                                 // Set word wrap to 50 characters
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = "$wsn Account Activation";
		$mail->Body    = $message;
		$mail->AltBody = strip_tags($message); 				  // Plain text alternative

		if(!$mail->send()) {
			echo 'Email could not be sent.';

			if ($debug) {
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
            showerror();
            return false;
		}
        return true;
	}

}
