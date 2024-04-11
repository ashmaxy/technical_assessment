<?php

namespace Base;

class Response
{
    protected $statusCodeDescription = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    ];

    protected $content;
    protected $headers = [];

    public function __construct () {
        $this->setHeader('Access-Control-Allow-Origin: *');
        $this->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->setHeader('Content-Type: application/json; charset=UTF-8');
    }

    public function getStatusCodeDescription($statusCode) {
        return isset($this->statusCodeDescription[$statusCode]) ? $this->statusCodeDescription[$statusCode] : 'unknown status';
    }

    public function setHeader(String $header) {
        $this->headers[] = $header;
    }

    public function getHeader() {
        return $this->headers;
    }

    public function setContent($content) {
        $this->content = json_encode($content);
    }

    public function getContent() {
        return $this->content;
    }

    public function checkStatusValid($statusCode) {
        if (array_key_exists($statusCode, $this->statusCodeDescription)) {
            return true;
        }

        return false;
    }

    public function setStatusCode($code) {
        if ($this->checkStatusValid($code)) {
            $this->setHeader(sprintf('HTTP/1.1 ' . $code . ' %s' , $this->getStatusCodeDescription($code)));
        }
    }

    public function send() {
        if ($this->content) {
            $output = $this->content;

            if (!headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header, true);
                }
            }
            
            echo $output;
        }
    }
}