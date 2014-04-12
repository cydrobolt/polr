<?php

    namespace Unirest;

    class HttpResponse
    {

        private $code;
        private $raw_body;
        private $body;
        private $headers;

        /**
         * @param int $code response code of the cURL request
         * @param string $raw_body the raw body of the cURL response
         * @param string $headers raw header string from cURL response
         */
        public function __construct($code, $raw_body, $headers)
        {
            $this->code = $code;
            $this->headers = $this->get_headers_from_curl_response($headers);
            $this->raw_body = $raw_body;
            $this->body = $raw_body;
            $json = json_decode($raw_body);
            
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->body = $json;
            }
        }

        /**
         * Return a property of the response if it exists.
         * Possibilities include: code, raw_body, headers, body (if the response is json-decodable)
         * @return mixed
         */
        public function __get($property)
        {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }

        /**
         * Set the properties of this object
         * @param string $property the property name
         * @param mixed $value the property value
         */
        public function __set($property, $value)
        {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
            return $this;
        }

        /**
         * Retrieve the cURL response headers from the
         * header string and convert it into an array
         * @param  string $headers header string from cURL response
         * @return array
         */
        private function get_headers_from_curl_response($headers)
        {
            $headers = explode("\r\n", $headers);
            array_shift($headers);

            foreach ($headers as $line) {
                if (strstr($line, ': ')) {
                    list ($key, $value) = explode(': ', $line);
                    $result[$key] = $value;
                }
            }

            return $result;
        }

    }