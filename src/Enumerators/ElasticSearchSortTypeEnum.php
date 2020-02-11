<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ElasticSearchSortTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ElasticSearchSortTypeEnum
{
    use EnumTricks;
    public const ASC = 0;
    public const DESC = 1;
}
