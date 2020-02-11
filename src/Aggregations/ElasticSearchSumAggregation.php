<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;

/**
 * Class ElasticSearchSumAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchSumAggregation extends ElasticSearchAggregation
{
    /**
     * ElasticSearchSumAggregation constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->type = ElasticSearchAggregationTypeEnum::SUM;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            $this->getSintaxOfAggregation() => [
                'field' => $this->value
            ]
        ];
    }

    /**
     * @param array $values
     * @return mixed
     */
    public function treatResponse(array $values)
    {
        return $values['value'];
    }
}
