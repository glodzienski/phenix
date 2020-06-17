<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use Logcomex\PhpUtils\Helpers\EnumHelper;

/**
 * Class ConditionDeterminantTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionDeterminantTypeEnum
{
    use EnumHelper;
    public const MUST = 'must';
    public const MUST_NOT = 'must_not';
    public const SHOULD = 'should';
}
