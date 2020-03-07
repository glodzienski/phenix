<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ElasticSearchConditionDeterminantTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ElasticSearchConditionDeterminantTypeEnum
{
    use EnumTricks;
    public const MUST = 'must';
    public const MUST_NOT = 'must_not';
    public const SHOULD = 'should';
}
