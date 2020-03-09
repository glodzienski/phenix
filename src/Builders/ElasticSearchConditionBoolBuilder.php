<?php

namespace glodzienski\AWSElasticsearchService\Builders;

use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchCondition;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionDeterminantTypeEnum;
use glodzienski\AWSElasticsearchService\Schema\ElasticSearchConditionBoolSchema;
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

    public function buildForRequest(): ElasticSearchConditionBoolSchema
    {
        $conditionBoolSchema = new ElasticSearchConditionBoolSchema();
        $conditions = $this->getConditions();

        $conditionBoolSchema->must = $conditions
            ->where('determinantType', ElasticSearchConditionDeterminantTypeEnum::MUST)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->toArray();

        $conditionBoolSchema->must_not = $conditions
            ->where('determinantType', ElasticSearchConditionDeterminantTypeEnum::MUST_NOT)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->toArray();

        $conditionBoolSchema->should = $conditions
            ->where('determinantType', ElasticSearchConditionDeterminantTypeEnum::SHOULD)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->toArray();

        $nestedConditions = $conditions
            ->filter(function ($condition) {
                return $condition instanceof ElasticSearchConditionBoolBuilder;
            })
            ->map(function (ElasticSearchConditionBoolBuilder $condition) {
                return $condition->buildForRequest();
            });

        return $conditionBoolSchema;
    }

    public function addCondition(ElasticSearchCondition $elasticSearchCondition)
    {
        $this->conditions->push($elasticSearchCondition);

        return $this;
    }

    public function addNestedBool(ElasticSearchConditionBoolBuilder $elasticSearchConditionBoolBuilder)
    {
        $this->nestedBools->push($elasticSearchConditionBoolBuilder);

        return $this;
    }
}
