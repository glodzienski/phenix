<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;

/**
 * Class ElasticSearchMatchCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchMatchCondition extends ElasticSearchCondition
{
    /**
     * @var string
     */
    private $logicalOperator = 'or';
    /**
     * ElasticSearchMatchCondition constructor.
     * @param string $field
     * @param string $value
     * @param string $conditionDeterminantType
     */
    public function __construct(string $field,
                                string $value,
                                string $conditionDeterminantType)
    {
        $this->type = ConditionTypeEnum::MATCH;
        $this->field = $field;
        $this->value = $value;
        $this->determinantType = $conditionDeterminantType;
    }

    /**
     * @param string $operator
     * @return $this
     */
    public function logicalOperator(string $operator)
    {
        $this->logicalOperator = $operator;

        return $this;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            $this->field => [
                'query' => $this->value,
                'operator' => $this->logicalOperator,
            ],
        ];
    }
}
