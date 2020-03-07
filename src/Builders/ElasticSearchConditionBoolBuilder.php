<?php

namespace glodzienski\AWSElasticsearchService\Builders;

use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchCondition;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionDeterminantTypeEnum;
use Illuminate\Support\Collection;

class ElasticSearchConditionBoolBuilder
{
    /**
     * @var Collection
     */
    protected $nestedBools;
    /**
     * @var Collection
     */
    private $conditions;

    /**
     * @return Collection
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    /**
     * @param Collection $conditions
     */
    public function setConditions(Collection $conditions): void
    {
        $this->conditions = $conditions;
    }

    /**
     * @return Collection
     */
    public function getNestedBools(): Collection
    {
        return $this->nestedBools;
    }

    /**
     * @param Collection $nestedBools
     */
    public function setNestedBools(Collection $nestedBools): void
    {
        $this->nestedBools = $nestedBools;
    }

    /**
     * ElasticSearchConditionBoolBuilder constructor.
     */
    public function __construct()
    {
        $this->conditions = collect();
        $this->nestedBools = collect();
    }

    public function hasNestedBools(): bool
    {
        return !empty($this->nestedBools);
    }

    public function buildForRequest(): array
    {
        $conditions = $this->getConditions();

        $conditions
            ->where('determinantType', ElasticSearchConditionDeterminantTypeEnum::MUST)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            });

        // get all must conditions
        // get all must not conditions
        // get all should conditions
        // get all nested conditions
    }
}
