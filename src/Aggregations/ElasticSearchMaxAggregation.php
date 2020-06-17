<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;

/**
 * Class ElasticSearchMaxAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchMaxAggregation extends ElasticSearchAggregation
{
    /**
     * ElasticSearchMaxAggregation constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->type = ElasticSearchAggregationTypeEnum::MAX;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function buildForRequest(): array
    {
        return [
            $this->getSyntaxOfAggregation() => [
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
