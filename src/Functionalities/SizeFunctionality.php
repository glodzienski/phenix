<?php

namespace App\ElasticSearch\Functionalities;

use glodzienski\AWSElasticsearchService\Contracts\SizeFunctionalityContract;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;

/**
 * Trait SizeFunctionality
 * @package App\ElasticSearch\Functionalities
 */
trait SizeFunctionality
{
    /**
     * @var int
     */
    public $size = 0;

    /**
     * @param int $rows
     * @return SizeFunctionality
     * @throws ElasticSearchException
     */
    public function take(int $rows): self
    {
        if ($this instanceof SizeFunctionalityContract) {
            $this->size = $rows;

            return $this;
        }

        throw new ElasticSearchException('Para utilizar função take na classe atual, você precisa contratar SizeFunctionalityContract.');
    }
}
