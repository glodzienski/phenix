<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;

/**
 * Class ElasticSearchPrefixCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchPrefixCondition extends ElasticSearchCondition
{
    /**
     * ElasticSearchPrefixCondition constructor.
     * @param string $field
     * @param string $value
     * @param string $conditionDeterminantType
     */
    public function __construct(string $field,
                                string $value,
                                string $conditionDeterminantType)
    {
        $this->type = ConditionTypeEnum::PREFIX;
        $this->field = $field;
        $this->value = $value;
        $this->determinantType = $conditionDeterminantType;
    }

    /**
     * @return array
     */
    public function buildForRequest(): array
    {
        return [
            $this->field => $this->value,
        ];
    }
}
