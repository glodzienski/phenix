<?php

namespace glodzienski\AWSElasticsearchService\Contracts;

interface SizeFunctionalityContract
{
    public function take(int $rows);
}
