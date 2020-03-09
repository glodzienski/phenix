<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ConditionDeterminantTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ConditionDeterminantTypeEnum
{
    use EnumTricks;
    public const MUST = 'must';
    public const MUST_NOT = 'must_not';
    public const SHOULD = 'should';
}
