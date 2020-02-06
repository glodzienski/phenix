<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

interface OffsetFunctionalityContract
{
    public function offset(int $row);
}
