# smtpapi-php

This php library allows you to quickly and more easily generate SendGrid X-SMTPAPI headers.

[![BuildStatus](https://api.travis-ci.org/sendgrid/smtpapi-php.png?branch=master)](https://travis-ci.org/sendgrid/smtpapi)
[![Latest Stable Version](https://poser.pugx.org/sendgrid/smtpapi/version.png)](https://packagist.org/packages/sendgrid/smtpapi)

## Installation

The following recommended installation requires [http://getcomposer.org](composer).

Add the following to your `composer.json` file.

```json
{  
  "minimum-stability" : "dev",
  "require": {
    "sendgrid/smtpapi": "0.0.1"
  }
}
``` 

Then at the top of your script require the autoloader:                 
 
```bash 
require 'vendor/autoload.php';                                         
``` 

## Usage

### Initializing

```php
$header    = new Smtpapi\Header();
```

### jsonString

This gives you back the stringified json formatted X-SMTPAPI header. Use this with your [smtp client](https://github.com/andris9/simplesmtp) of choice.

```php
$header    = new Smtpapi\Header();
$header->jsonString();
```

### addTo

```php
$header    = new Smtpapi\Header();
$header->addTo('you@youremail.com');
$header->addTo('other@otheremail.com');
```

### setTos

```php
$header    = new Smtpapi\Header();
$header->setTos(array('you@youremail.com', 'other@otheremail.com'));
```

### addSubstitution

```php
$header    = new Smtpapi\Header();
$header->addSubstitution('keep', array('secret')); // sub = {keep: ['secret']}
header->addSubstitution('other', array('one', 'two'));   // sub = {keep: ['secret'], other: ['one', 'two']}
```

### setSubstitutions

```php
$header    = new Smtpapi\Header();
$header->setSubstitutions(array('keep' => array('secret'))); // sub = {keep: ['secret']}
```
### addUniqueArg

```php
$header    = new Smtpapi\Header();
$header->addUniqueArg('cat', 'dogs');
```

### setUniqueArgs

```php
$header    = new Smtpapi\Header();
$header->setUniqueArgs(array('cow' => 'chicken'));
$header->setUniqueArgs(array('dad' => 'proud'));
```

### addCategory

```php
$header    = new Smtpapi\Header();
$header->addCategory('tactics'); // category = ['tactics']
$header->addCategory('advanced'); // category = ['tactics', 'advanced']
```

### setCategories

```php
$header    = new Smtpapi\Header();
$header->setCategories(array('tactics', 'advanced')); // category = ['tactics', 'advanced']
```

### addSection

```php
$header    = new Smtpapi\Header();
$header->addSection('-charge-': 'This ship is useless.');
$header->addSection('-bomber-', 'Only for sad vikings.');
```

### setSections

```php
$header    = new Smtpapi\Header();
$header->setSections(array('-charge-' => 'This ship is useless.'));
```

### addFilter

```php
$header    = new Smtpapi\Header();
$header->addFilter('footer', 'enable', 1);
$header->addFilter('footer', 'text/html', '<strong>boo</strong>');
```

### setFilters

```php
$header    = new Smtpapi\Header();
$filter = array( 
  'footer' => array( 
    'setting' => array( 
      'enable' => 1,
      "text/plain" => 'You can haz footers!'
    )
  )
); 
$header->setFilters($filter);
```

## SendGrid SMTP Example

The following example builds the X-SMTPAPI headers and adds them to swiftmailer. [Swiftmailer](http://swiftmailer.org/) then sends the email through SendGrid. You can use this same code in your application or optionally you can use [sendgrid-php](http://github.com/sendgrid/sendgrid-php).

```php
$transport  = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
$transport->setUsername("sendgrid_username");
$transport->setPassword("sendgrid_password");

$mailer     = Swift_Mailer::newInstance($transport);

$message    = new Swift_Message();
$message->setTos(array("bar@blurdybloop.com"));
$message->setFrom("foo@blurdybloop.com");
$message->setSubject("Hello");
$message->setBody("%how% are you doing?");

$header           = new Smtpapi\Header();
$header->addSubstitution("%how%", array("Owl"));

$message_headers  = $message->getHeaders();
$message_headers->addTextHeader("x-smtpapi", $header->jsonString());

try {
  $response = $mailer->send($message);
  print_r($response);
} catch(\Swift_TransportException $e) {
  print_r('Bad username / password');
}
```

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Added some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

## Running Tests

The existing tests in the `test` directory can be run using [PHPUnit](https://github.com/sebastianbergmann/phpunit/) with the following command:

````bash
composer update --dev
cd test
../vendor/bin/phpunit
```

or if you already have PHPUnit installed globally.

```bash
cd test
phpunit
```
