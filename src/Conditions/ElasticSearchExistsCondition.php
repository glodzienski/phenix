<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;

/**
 * Class ElasticSearchExistsCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchExistsCondition extends ElasticSearchCondition
{
    /**
     * ElasticSearchExistsCondition constructor.
     * @param string $field
     * @param string $conditionDeterminantType
     */
    public function __construct(string $field, string $conditionDeterminantType)
    {
        $this->type = ConditionTypeEnum::EXISTS;
        $this->value = null;
        $this->field = $field;
        $this->determinantType = $conditionDeterminantType;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            'field' => $this->field
        ];
    }
}
