<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Contracts\OffsetFunctionalityContract;
use glodzienski\AWSElasticsearchService\Contracts\SizeFunctionalityContract;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;
use glodzienski\AWSElasticsearchService\Functionalities\OffsetFunctionality;
use glodzienski\AWSElasticsearchService\Functionalities\SizeFunctionality;

/**
 * Class ElasticSearchBucketSortAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchBucketSortAggregation
    extends ElasticSearchAggregation
    implements SizeFunctionalityContract, OffsetFunctionalityContract
{
    use SizeFunctionality,
        OffsetFunctionality;

    /**
     * ElasticSearchBucketSortAggregation constructor.
     * @param string $name
     * @throws ElasticSearchException
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
        $aggregationSyntax = [];

        if (!empty($this->value)) {
            $aggregationSyntax['sort'] = $this->value;
        }

        if (!empty($this->from)) {
            $aggregationSyntax['from'] = $this->from;
        }

        if (!empty($this->size)) {
            $aggregationSyntax['size'] = $this->size;
        }

        return [
            $this->getSyntaxOfAggregation() => $aggregationSyntax
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

    /**
     * @param string $aggregationPath
     * @param $sortType
     * @return $this
     */
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
