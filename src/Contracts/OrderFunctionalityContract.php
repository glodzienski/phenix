<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

/**
 * Interface OrderFunctionalityContract
 * @package glodzienski\AWSElasticsearchService\Contracts
 */
interface OrderFunctionalityContract
{
    /**
     * @param array $payload
     * @return mixed
     */
    public function sort(array $payload);
}
