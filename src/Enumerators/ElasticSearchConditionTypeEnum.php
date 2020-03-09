<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ElasticSearchConditionTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ElasticSearchConditionTypeEnum
{
    use EnumTricks;
    public const TERM = 'term';
    public const RANGE = 'range';
}
