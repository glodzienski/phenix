<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use Logcomex\PhpUtils\Helpers\EnumHelper;

/**
 * Class ConditionTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionTypeEnum
{
    use EnumHelper;
    public const TERM = 'term';
    public const TERMS = 'terms';
    public const RANGE = 'range';
    public const MATCH = 'match';
    public const MATCH_PHRASE = 'match_phrase';
    public const MULTI_MATCH = 'multi_match';
    public const EXISTS = 'exists';
    public const PREFIX = 'prefix';
}
