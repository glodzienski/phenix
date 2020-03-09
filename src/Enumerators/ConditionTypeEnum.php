<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ConditionTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionTypeEnum
{
    use EnumTricks;
    public const TERM = 'term';
    public const TERMS = 'terms';
    public const RANGE = 'range';
    public const MATCH = 'match';
    public const MULTI_MATCH = 'multi_match';
    public const EXISTS = 'exists';
    public const PREFIX = 'prefix';
}
