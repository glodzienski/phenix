<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;

/**
 * Class ElasticSearchStatsBucketAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchStatsBucketAggregation extends ElasticSearchAggregation
{
    /**
     * ElasticSearchStatsBucketAggregation constructor.
     * @param string $name
     * @param string $bucketsPath
     */
    public function __construct(string $name, string $bucketsPath)
    {
        $this->type = ElasticSearchAggregationTypeEnum::STATS_BUCKET;
        $this->name = $name;
        $this->value = $bucketsPath;

        return $this;
    }

    /**
     * @return array
     */
    public function buildForRequest(): array
    {
        return [
            $this->getSyntaxOfAggregation() => [
                'buckets_path' => $this->value
            ]
        ];
    }

    /**
     * @param array $values
     * @return array|mixed
     */
    public function treatResponse(array $values)
    {
        return $values;
    }
}
