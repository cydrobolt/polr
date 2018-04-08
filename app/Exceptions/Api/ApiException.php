<?php
namespace App\Exceptions\Api;

class ApiException extends \Exception {
    /**
     * Catch an API exception.
     *
     * @param string $text_code
     * @param string $message
     * @param integer $status_code
     * @param string $response_type
     * @param \Exception $previous
     *
     * @return mixed
     */
    public function __construct($text_code='SERVER_ERROR', $message, $status_code = 0, $response_type='plain_text', Exception $previous = null) {
        $this->response_type = $response_type;
        $this->text_code = $text_code;
        parent::__construct($message, $status_code, $previous);
    }

    private function encodeJsonResponse($status_code, $message, $text_code) {
        $response = [
            'status_code' => $status_code,
            'error_code' => $text_code,
            'error' => $message
        ];

        return json_encode($response);
    }

    public function getEncodedErrorMessage() {
        if ($this->response_type == 'json') {
            return $this->encodeJsonResponse($this->code, $this->message, $this->text_code);
        }
        else {
            return $this->code . ' ' . $this->message;
        }
    }
}
