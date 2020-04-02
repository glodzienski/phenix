<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

/**
 * Interface SortFunctionalityContract
 * @package glodzienski\AWSElasticsearchService\Contracts
 */
interface SortFunctionalityContract
{
    /**
     * @param string $by
     * @param string $type
     * @return mixed
     */
    public function sort(string $by, string $type);
}
