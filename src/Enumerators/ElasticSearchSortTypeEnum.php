<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use Logcomex\PhpUtils\Helpers\EnumHelper;

/**
 * Class ElasticSearchSortTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ElasticSearchSortTypeEnum
{
    use EnumHelper;
    public const ASC = 0;
    public const DESC = 1;
}
