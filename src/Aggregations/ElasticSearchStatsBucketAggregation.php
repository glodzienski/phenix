<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use App\ElasticSearch\ElasticSearchAggregationTypeEnum;

class ElasticSearchStatsBucketAggregation extends ElasticSearchAggregation
{
    public function __construct(string $name, string $bucketsPath)
    {
        $this->type = ElasticSearchAggregationTypeEnum::STATS_BUCKET;
        $this->name = $name;
        $this->value = $bucketsPath;

        return $this;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            $this->getSintaxOfAggregation() => [
                'buckets_path' => $this->value
            ]
        ];
    }

    public function treatResponse(array $values)
    {
        return $values;
    }
}
