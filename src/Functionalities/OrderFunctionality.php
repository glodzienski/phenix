<?php

namespace glodzienski\AWSElasticsearchService\Functionalities;

use glodzienski\AWSElasticsearchService\Contracts\OrderFunctionalityContract;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;

/**
 * Trait OrderFunctionality
 * @package glodzienski\AWSElasticsearchService\Functionalities
 */
trait OrderFunctionality
{
    /**
     * @var int
     */
    public $size;

    /**
     * @param int $rows
     * @return OrderFunctionality
     * @throws ElasticSearchException
     */
    public function take(int $rows): self
    {
        if ($this instanceof OrderFunctionalityContract) {
            $this->size = $rows;

            return $this;
        }

        throw new ElasticSearchException('Para utilizar função take na classe atual, você precisa contratar OrderFunctionalityContract.');
    }
}
