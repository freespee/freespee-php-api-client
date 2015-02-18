<?php namespace Freespee\ApiClient;

class Response
{
    var $httpCode;
    var $result;

    const OK = 200;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    public function __construct($httpCode = null, $result = null)
    {
        $this->httpCode = $httpCode;
        $this->result = $result;
    }
}
