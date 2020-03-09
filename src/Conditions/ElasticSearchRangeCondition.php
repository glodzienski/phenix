<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionRangeTypeEnum;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionTypeEnum;
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
     * @param string $value
     * @param string $conditionDeterminantType
     * @param string $rangeType
     * @throws ElasticSearchException
     * @throws \ReflectionException
     */
    public function __construct(string $field,
                                string $value,
                                string $conditionDeterminantType,
                                string $rangeType)
    {
        if (!in_array($rangeType, ConditionRangeTypeEnum::all())) {
            throw new ElasticSearchException("Condition range type: {$rangeType} doesn't exists.");
        }

        $this->type = ElasticSearchConditionTypeEnum::RANGE;
        $this->field = $field;
        $this->value = $value;
        $this->determinantType = $conditionDeterminantType;
        $this->rangeType = $rangeType;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            $this->field => [
                $this->rangeType => $this->value,
            ],
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
}
