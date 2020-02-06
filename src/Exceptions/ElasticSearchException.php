<?php

namespace glodzienski\AWSElasticsearchService\Exceptions;

class ElasticSearchException extends \Exception
{
    public $statusCode;

    public function __construct($message, $code = 500, \Exception $previous = null) {
        $this->statusCode = $code;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getStatusCode() {
        return $this->statusCode;
    }
}
