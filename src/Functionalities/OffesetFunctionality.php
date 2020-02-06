<?php

namespace glodzienski\AWSElasticsearchService\Functionalities;

use glodzienski\AWSElasticsearchService\Contracts\OffsetFunctionalityContract;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;

trait OffesetFunctionality
{
    public $from = 0;

    public function offset(int $row): self
    {
        if ($this instanceof OffsetFunctionalityContract) {
            $this->from = $row;

            return $this;
        }

        throw new ElasticSearchException('Para utilizar função offset na classe atual, você precisa contratar OffsetFunctionalityContract.');
    }
}
