<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

/**
 * Interface OffsetFunctionalityContract
 * @package glodzienski\AWSElasticsearchService\Contracts
 */
interface OffsetFunctionalityContract
{
    /**
     * @param int $row
     * @return mixed
     */
    public function offset(int $row);
}
