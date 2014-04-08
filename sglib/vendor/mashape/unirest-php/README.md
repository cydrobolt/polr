<hr>
**To the community**: At this time Unirest-PHP only support syncronous requests, and I would really love to implement  asynchronous support. If you guys have any feedback or ideas please comment on issue <a href="https://github.com/Mashape/unirest-php/issues/23">#23</a>.
<hr>

# Unirest for PHP [![Build Status](https://api.travis-ci.org/Mashape/unirest-php.png)](https://travis-ci.org/Mashape/unirest-php)

Unirest is a set of lightweight HTTP libraries available in multiple languages, ideal for most applications:

* Make `GET`, `POST`, `PUT`, `PATCH`, `DELETE` requests
* It supports form parameters, file uploads and custom body entities
* Supports gzip
* Supports Basic Authentication natively
* Customizable timeout
* Customizable default headers for every request (DRY)
* Automatic JSON parsing into a native object for JSON responses

Created with love by [thefosk](https://github.com/thefosk) @ [mashape.com](https://mashape.com)

### Install with Composer
If you're using [Composer](https://github.com/composer/composer) to manage
dependencies, you can add Unirest with it.

```javascript
{
  "require" : {
    "mashape/unirest-php" : "dev-master"
  },
  "autoload": {
    "psr-0": {"Unirest": "lib/"}
  }
}
```

### Install source from GitHub
Unirest-PHP requires PHP `v5.3+`. Download the PHP library from Github, and require in your script like so:

To install the source code:

```bash
$ git clone git@github.com:Mashape/unirest-php.git 
```

And include it in your scripts:

```bash
require_once '/path/to/unirest-php/lib/Unirest.php';
```

## Creating Request

So you're probably wondering how using Unirest makes creating requests in PHP easier, let's look at a working example:

```php
$response = Unirest::post("http://httpbin.org/post", array( "Accept" => "application/json" ),
  array(
    "parameter" => 23,
    "foo" => "bar"
  )
);

$response->code; // HTTP Status code
$response->headers; // Headers
$response->body; // Parsed body
$response->raw_body; // Unparsed body
```

### File Uploads

To upload files in a multipart form representation use the return value of `Unirest::file($path)` as the value of a parameter:

```php
$response = Unirest::post("http://httpbin.org/post", array( "Accept" => "application/json" ),
  array(
    "file" => Unirest::file("/tmp/file.txt") // Tells Unirest where the file is located
  )
);
 ```
 
### Custom Entity Body
Sending a custom body such as a JSON Object rather than a string or form style parameters we utilize json_encode for the body:
```php
$response = Unirest::post("http://httpbin.org/post", array( "Accept" => "application/json" ),
  json_encode(
    array(
      "parameter" => "value",
      "foo" => "bar"
    )
  )
);
```

### Basic Authentication

Authenticating the request with basic authentication can be done by providing the `username` and `password` arguments:

```php
$response = Unirest::get("http://httpbin.org/get", null, null, "username", "password");
```

# Request
```php
Unirest::get($url, $headers = array(), $parameters = NULL, $username = NULL, $password = NULL)
Unirest::post($url, $headers = array(), $body = NULL, $username = NULL, $password = NULL)
Unirest::put($url, $headers = array(), $body = NULL, $username = NULL, $password = NULL)
Unirest::patch($url, $headers = array(), $body = NULL, $username = NULL, $password = NULL)
Unirest::delete($url, $headers = array(), $body = NULL, $username = NULL, $password = NULL)
```
  
- `url` - Endpoint, address, or uri to be acted upon and requested information from.
- `headers` - Request Headers as associative array or object
- `body` - Request Body as associative array or object
- `username` - Basic Authentication username
- `password` - Basic Authentication password

# Response
Upon recieving a response Unirest returns the result in the form of an Object, this object should always have the same keys for each language regarding to the response details.

- `code` - HTTP Response Status Code (Example `200`)
- `headers` - HTTP Response Headers
- `body` - Parsed response body where applicable, for example JSON responses are parsed to Objects / Associative Arrays.
- `raw_body` - Un-parsed response body

# Advanced Configuration

You can set some advanced configuration to tune Unirest-PHP:

### Timeout

You can set a custom timeout value (in **seconds**):

```php
Unirest::timeout(5); // 5s timeout
```

### Default Request Headers

You can set default headers that will be sent on every request:

```php
Unirest::defaultHeader("Header1", "Value1");
Unirest::defaultHeader("Header2", "Value2");
```

You can clear the default headers anytime with:

```php
Unirest::clearDefaultHeaders();
```

### SSL validation

You can explicitly enable or disable SSL certificate validation when consuming an SSL protected endpoint:

```php
Unirest::verifyPeer(false); // Disables SSL cert validation
```

By default is `true`.
