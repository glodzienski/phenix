<?php

namespace glodzienski\AWSElasticsearchService\Functionalities;

use glodzienski\AWSElasticsearchService\Contracts\OffsetFunctionalityContract;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;

/**
 * Trait OffesetFunctionality
 * @package glodzienski\AWSElasticsearchService\Functionalities
 */
trait OffesetFunctionality
{
    /**
     * @var int
     */
    public $from = 0;

    /**
     * @param int $row
     * @return $this
     * @throws ElasticSearchException
     */
    public function offset(int $row): self
    {
        if ($this instanceof OffsetFunctionalityContract) {
            $this->from = $row;

            return $this;
        }

        throw new ElasticSearchException('Para utilizar função offset na classe atual, você precisa contratar OffsetFunctionalityContract.');
    }
}
