<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use App\ElasticSearch\Contracts\SizeFunctionalityContract;
use App\ElasticSearch\ElasticSearchAggregationResponseHandler;
use App\ElasticSearch\ElasticSearchAggregationTypeEnum;
use App\ElasticSearch\Functionalities\SizeFunctionality;

class ElasticSearchCompositeAggregation extends ElasticSearchAggregation implements SizeFunctionalityContract
{
    use SizeFunctionality;

    /**
     * ElasticSearchCompositeAggregation constructor.
     * @param string $name
     * @throws \App\Exceptions\ElasticSearchException
     */
    public function __construct(string $name)
    {
        $this->type = ElasticSearchAggregationTypeEnum::COMPOSITE;
        $this->name = $name;
        // Essa agregação retorna buckets, então as subagregações
        // devem ser tratadas dentro da própria agregação
        $this->handleSubAggsHimself = true;
        $this->take(100);

        return $this;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        $payload = [
            'size' => $this->size,
            'sources' => [],
        ];

        if (empty($this->value)) {
            return $payload;
        }

        foreach ($this->value as $agg) {
            $sourceAggBodyPayload = $agg->buildForRequest();
            // Não precisa do size para o sources do composite
            unset($sourceAggBodyPayload['terms']['size']);

            $payload['sources'][][$agg->name] = $sourceAggBodyPayload;
        }

        return [
            $this->getSintaxOfAggregation() => $payload
        ];
    }

    public function treatResponse(array $values)
    {
        $treated = [];

        $buckets = $values['buckets'];
        $nextRowOfResult = $values['after_key'];

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
                    $aggChildValue = $bucket[$aggChild->name] ?? [];
                    if (empty($aggChildValue)) {
                        $bucketTreated[$aggChild->name] = 'no result';
                        continue;
                    }

                    $bucketTreated[$aggChild->name] = ElasticSearchAggregationResponseHandler::treat($aggChild, $aggChildValue);
                }
            }

            $treated[] = $bucketTreated;
        }

        return [
            'data' => $treated,
            'next_row' => $nextRowOfResult
        ];
    }

    public function addSource(ElasticSearchAggregation $aggregation): self
    {
        $this->value[] = $aggregation;

        return $this;
    }
}
