<?php

class Smtpapi {
  const VERSION = "0.0.1";

  public static function register_autoloader() {
    spl_autoload_register(array('Smtpapi', 'autoloader'));
  }

  public static function autoloader($class) {
    // Check that the class starts with "Smtpapi"
    if ($class == 'Smtpapi' || stripos($class, 'Smtpapi\\') === 0) {
      $file = str_replace('\\', '/', $class);

      if (file_exists(dirname(__FILE__) . '/' . $file . '.php')) {
        require_once(dirname(__FILE__) . '/' . $file . '.php');
      }
    }
  }
}
