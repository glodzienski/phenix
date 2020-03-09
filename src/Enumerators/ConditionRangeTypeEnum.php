<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ConditionRangeTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionRangeTypeEnum
{
    use EnumTricks;
    public const GREATER_THAN = 'gt';
    public const LESS_THAN = 'lt';
    public const GREATER_THAN_OR_EQUAL = 'gte';
    public const LESS_THAN_OR_EQUAL = 'lte';
    public const BETWEEN = 'between';
}
