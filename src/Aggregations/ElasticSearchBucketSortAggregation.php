<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use App\ElasticSearch\Contracts\OffsetFunctionalityContract;
use App\ElasticSearch\Contracts\SizeFunctionalityContract;
use App\ElasticSearch\ElasticSearchAggregationTypeEnum;
use App\ElasticSearch\Functionalities\OffesetFunctionality;
use App\ElasticSearch\Functionalities\SizeFunctionality;

class ElasticSearchBucketSortAggregation
    extends ElasticSearchAggregation
    implements SizeFunctionalityContract, OffsetFunctionalityContract
{
    use SizeFunctionality,
        OffesetFunctionality;

    /**
     * ElasticSearchBucketSortAggregation constructor.
     * @param string $name
     * @throws \App\Exceptions\ElasticSearchException
     */
    public function __construct(string $name)
    {
        $this->type = ElasticSearchAggregationTypeEnum::BUCKET_SORT;
        $this->name = $name;
        // Essa agregação não tem retorno, ela
        // somente altera o retorno de outras agregações
        $this->ignoreHandler = true;
        $this->take(15);

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
                'sort' => $this->value,
                'from' => $this->from,
                'size' => $this->size
            ]
        ];
    }

    public function treatResponse(array $values)
    {
        return $values['value'];
    }


    public function addSorter(string $aggregationPath, $sortType): self
    {
        $this->value[] = [
            $aggregationPath => [
                'order' => $sortType
            ]
        ];

        return $this;
    }
}
