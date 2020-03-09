<?php

namespace glodzienski\AWSElasticsearchService\Builders;

use glodzienski\AWSElasticsearchService\Conditions\ElasticSearchCondition;
use glodzienski\AWSElasticsearchService\Enumerators\ConditionDeterminantTypeEnum;
use glodzienski\AWSElasticsearchService\Schema\ElasticSearchConditionBoolSchema;
use Illuminate\Support\Collection;

/**
 * Class ElasticSearchConditionBoolBuilder
 * @package glodzienski\AWSElasticsearchService\Builders
 */
class ElasticSearchConditionBoolBuilder
{
    /**
     * @var Collection
     */
    private $nestedBools;
    /**
     * @var ElasticSearchConditionBoolBuilder
     */
    private $boolParent;
    /**
     * @var string
     */
    private $boolDeterminantType = ConditionDeterminantTypeEnum::MUST;
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
        $this->boolParent = null;
    }

    /**
     * @return bool
     */
    public function hasNestedBools(): bool
    {
        return !empty($this->nestedBools);
    }

    /**
     * @param Collection $conditions
     * @return array
     */
    private function buildMustConditions(Collection $conditions): array
    {
        return $conditions
            ->where('determinantType', ConditionDeterminantTypeEnum::MUST)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * @param Collection $conditions
     * @return array
     */
    private function buildMustNotConditions(Collection $conditions): array
    {
        return $conditions
            ->where('determinantType', ConditionDeterminantTypeEnum::MUST_NOT)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * @param Collection $conditions
     * @return array
     */
    private function buildShouldConditions(Collection $conditions): array
    {
        return $conditions
            ->where('determinantType', ConditionDeterminantTypeEnum::SHOULD)
            ->map(function (ElasticSearchCondition $condition) {
                return [
                    $condition->getSintax() => $condition->buildForRequest()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * @param ElasticSearchConditionBoolSchema $conditionBoolSchema
     * @return ElasticSearchConditionBoolSchema
     */
    private function buildNestedBools(ElasticSearchConditionBoolSchema $conditionBoolSchema): ElasticSearchConditionBoolSchema
    {
        $this->nestedBools
            ->each(function (ElasticSearchConditionBoolBuilder $conditionBoolBuilder) use (&$conditionBoolSchema) {
                $buildedPayload = ['bool' => $conditionBoolBuilder->buildForRequest()];

                $conditionBoolBuilderDeterminantType = $conditionBoolBuilder->getBoolDeterminantType();
                $conditionBoolSchema->{$conditionBoolBuilderDeterminantType}[] = $buildedPayload;
            });

        return $conditionBoolSchema;
    }

    /**
     * @return ElasticSearchConditionBoolSchema
     */
    public function buildForRequest(): ElasticSearchConditionBoolSchema
    {
        $conditionBoolSchema = new ElasticSearchConditionBoolSchema();
        $conditions = $this->getConditions();

        $conditionBoolSchema->must = $this->buildMustConditions($conditions);
        $conditionBoolSchema->must_not = $this->buildMustNotConditions($conditions);
        $conditionBoolSchema->should = $this->buildShouldConditions($conditions);
        if ($this->hasNestedBools()) {
            $conditionBoolSchema = $this->buildNestedBools($conditionBoolSchema);
        }

        return $conditionBoolSchema;
    }

    /**
     * @param ElasticSearchCondition $elasticSearchCondition
     * @return $this
     */
    public function addCondition(ElasticSearchCondition $elasticSearchCondition)
    {
        $this->conditions->push($elasticSearchCondition);

        return $this;
    }

    /**
     * @param ElasticSearchConditionBoolBuilder $elasticSearchConditionBoolBuilder
     * @return $this
     */
    public function addNestedBool(ElasticSearchConditionBoolBuilder $elasticSearchConditionBoolBuilder)
    {
        $this->nestedBools->push($elasticSearchConditionBoolBuilder);

        return $this;
    }

    /**
     * @return bool
     */
    public function isNested(): bool
    {
        return isset($this->boolParent);
    }

    /**
     * @return ElasticSearchConditionBoolBuilder
     */
    public function getBoolParent(): ElasticSearchConditionBoolBuilder
    {
        return $this->boolParent;
    }

    /**
     * @param ElasticSearchConditionBoolBuilder $boolParent
     * @return $this
     */
    public function setBoolParent(ElasticSearchConditionBoolBuilder $boolParent)
    {
        $this->boolParent = $boolParent;

        return $this;
    }

    /**
     * @return string
     */
    public function getBoolDeterminantType(): string
    {
        return $this->boolDeterminantType;
    }

    /**
     * @param string $boolDeterminantType
     * @return $this
     */
    public function setBoolDeterminantType(string $boolDeterminantType)
    {
        $this->boolDeterminantType = $boolDeterminantType;

        return $this;
    }
}
