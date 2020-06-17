<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Contracts\SizeFunctionalityContract;
use glodzienski\AWSElasticsearchService\Contracts\SortFunctionalityContract;
use glodzienski\AWSElasticsearchService\Functionalities\SortFunctionality;
use glodzienski\AWSElasticsearchService\Handlers\ElasticSearchAggregationResponseHandler;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;
use glodzienski\AWSElasticsearchService\Functionalities\SizeFunctionality;

/**
 * Class ElasticSearchTermsAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchTermsAggregation extends ElasticSearchAggregation implements SizeFunctionalityContract, SortFunctionalityContract
{
    use SizeFunctionality, SortFunctionality;

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
     */
    public function buildForRequest(): array
    {
        $payload = ['field' => $this->value];
        if (isset($this->size)) {
            $payload['size'] = $this->size;
        }
        if ($this->hasSorter()) {
            $payload['order'] = [
                $this->sortBy => $this->sortType
            ];
        }

        return [
            $this->getSyntaxOfAggregation() => $payload
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
                    $aggregationResponse = $bucket[$aggChild->name] ?? [];
                    $bucketTreated[$aggChild->name] = ElasticSearchAggregationResponseHandler::treat($aggChild, $aggregationResponse);
                }
            }
            $treated[] = $bucketTreated;
        }
        return $treated;
    }
}
