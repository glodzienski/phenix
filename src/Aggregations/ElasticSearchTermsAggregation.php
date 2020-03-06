<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Contracts\SizeFunctionalityContract;
use glodzienski\AWSElasticsearchService\Handlers\ElasticSearchAggregationResponseHandler;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;
use glodzienski\AWSElasticsearchService\Functionalities\SizeFunctionality;

/**
 * Class ElasticSearchTermsAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchTermsAggregation extends ElasticSearchAggregation implements SizeFunctionalityContract
{
    use SizeFunctionality;

    /**
     * ElasticSearchTermsAggregation constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->type = ElasticSearchAggregationTypeEnum::TERMS;
        $this->name = $name;
        $this->value = $value;
        $this->handleSubAggsHimself = true;

        return $this;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        $payload = ['field' => $this->value];

        if (isset($this->size)) {
            $payload['size'] = $this->size;
        }

        return [
            $this->getSintaxOfAggregation() => $payload
        ];
    }

    /**
     * @param array $values
     * @return array
     */
    public function treatResponse(array $values)
    {
        $treated = [];

        $buckets = $values['buckets'];

        if (empty($buckets)) {
            return $treated;
        }

        foreach ($buckets as $bucket) {
            $bucketTreated = [
                'key' => $bucket['key'],
                'count' => $bucket['doc_count']
            ];

            if ($this->hasChildren()) {
                foreach ($this->getChildren() as $aggChild) {
                    $bucketTreated[$aggChild->name] = ElasticSearchAggregationResponseHandler::treat($aggChild, $bucket[$aggChild->name]);
                }
            }

            $treated[] = $bucketTreated;
        }

        return $treated;
    }
}
