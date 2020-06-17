<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionRangeTypeEnum;
use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;
use glodzienski\AWSElasticsearchService\Exceptions\ElasticSearchException;

/**
 * Class ElasticSearchRangeCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchRangeCondition extends ElasticSearchCondition
{
    /**
     * @var string
     */
    private $rangeType = ConditionRangeTypeEnum::GREATER_THAN;

    /**
     * ElasticSearchRangeCondition constructor.
     * @param string $field
     * @param mixed $value
     * @param string $conditionDeterminantType
     * @param string $rangeType
     * @throws ElasticSearchException
     */
    public function __construct(string $field,
                                $value,
                                string $conditionDeterminantType,
                                string $rangeType)
    {
        $this->rangeType = $rangeType;

        if (!in_array($rangeType, ConditionRangeTypeEnum::all())) {
            throw new ElasticSearchException("Condition range type: {$rangeType} doesn't exists.");
        }

        if ($this->isBetweenRangeType() && !is_array($value)) {
            throw new ElasticSearchException("When you use the between range type, your value must be an array with two numbers.");
        }

        $this->type = ConditionTypeEnum::RANGE;
        $this->field = $field;
        $this->value = $value;
        $this->determinantType = $conditionDeterminantType;
    }

    private function isBetweenRangeType(): bool
    {
        return $this->rangeType === ConditionRangeTypeEnum::BETWEEN;
    }

    /**
     * @return array
     */
    public function buildForRequest(): array
    {
        if ($this->isBetweenRangeType()) {
            return [
                $this->field => [
                    ConditionRangeTypeEnum::GREATER_THAN_OR_EQUAL => $this->value[0],
                    ConditionRangeTypeEnum::LESS_THAN_OR_EQUAL => $this->value[1],
                ],
            ];
        }

        return [
            $this->field => [
                $this->rangeType => $this->value,
            ],
        ];
    }
}
