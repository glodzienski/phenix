<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

/**
 * Interface SizeFunctionalityContract
 * @package glodzienski\AWSElasticsearchService\Contracts
 */
interface SizeFunctionalityContract
{
    /**
     * @param int $rows
     * @return mixed
     */
    public function take(int $rows);
}
