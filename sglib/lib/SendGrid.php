<?php

class SendGrid {
  const VERSION = "2.0.3";

  protected $namespace  = "SendGrid",
            $url        = "https://api.sendgrid.com/api/mail.send.json",
            $headers    = array('Content-Type' => 'application/json'),
            $username,
            $password,
            $options,
            $web;
  
  public function __construct($username, $password, $options=array("turn_off_ssl_verification" => true)) {
    $this->username = $username;
    $this->password = $password;
    $this->options  = $options;
  }

  public function send(SendGrid\Email $email) {
    $form             = $email->toWebFormat();
    $form['api_user'] = $this->username; 
    $form['api_key']  = $this->password; 

    // option to ignore verification of ssl certificate
    if (isset($this->options['turn_off_ssl_verification']) && isset($this->options['turn_off_ssl_verification']) && $this->options['turn_off_ssl_verification'] == true) {
      \Unirest::verifyPeer(false);
    }

    $response = \Unirest::post($this->url, array(), $form);

    return $response->body;
  }

  public static function register_autoloader() {
    spl_autoload_register(array('SendGrid', 'autoloader'));
  }

  public static function autoloader($class) {
    // Check that the class starts with "SendGrid"
    if ($class == 'SendGrid' || stripos($class, 'SendGrid\\') === 0) {
      $file = str_replace('\\', '/', $class);

      if (file_exists(dirname(__FILE__) . '/' . $file . '.php')) {
        require_once(dirname(__FILE__) . '/' . $file . '.php');
      }
    }
  }
}
