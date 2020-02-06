<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use App\ElasticSearch\ElasticSearchAggregationTypeEnum;

class ElasticSearchValueCountAggregation extends ElasticSearchAggregation
{
    public function __construct(string $name, string $value)
    {
        $this->type = ElasticSearchAggregationTypeEnum::VALUE_COUNT;
        $this->name = $name;
        $this->value = $value;

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
                'field' => $this->value
            ]
        ];
    }

    public function treatResponse(array $values)
    {
        return $values['value'];
    }
}
