<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

/**
 * Class ElasticSearchAggregationTypeEnum
 * @package glodzienski\AWSElasticsearchService\Enumerators
 */
class ElasticSearchAggregationTypeEnum
{
    use EnumTricks;

    public const MIN = 0;
    public const AVG = 1;
    public const MAX = 2;
    public const SUM = 3;
    public const VALUE_COUNT = 4;
    public const TERMS = 5;
    public const STATS_BUCKET = 6;
    public const COMPOSITE = 7;
    public const BUCKET_SORT = 8;
}
