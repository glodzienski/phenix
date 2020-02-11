<?php

namespace glodzienski\AWSElasticsearchService\Exceptions;

/**
 * Class ElasticSearchException
 * @package glodzienski\AWSElasticsearchService\Exceptions
 */
class ElasticSearchException extends \Exception
{
    /**
     * @var int
     */
    public $statusCode;

    /**
     * ElasticSearchException constructor.
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 500, \Exception $previous = null) {
        $this->statusCode = $code;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }
}
