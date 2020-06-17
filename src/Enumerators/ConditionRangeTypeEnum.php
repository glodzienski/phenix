<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use Logcomex\PhpUtils\Helpers\EnumHelper;

/**
 * Class ConditionRangeTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionRangeTypeEnum
{
    use EnumHelper;
    public const GREATER_THAN = 'gt';
    public const LESS_THAN = 'lt';
    public const GREATER_THAN_OR_EQUAL = 'gte';
    public const LESS_THAN_OR_EQUAL = 'lte';
    public const BETWEEN = 'between';
}
