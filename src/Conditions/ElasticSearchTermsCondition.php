<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;

/**
 * Class ElasticSearchTermsCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchTermsCondition extends ElasticSearchCondition
{
    /**
     * ElasticSearchTermsCondition constructor.
     * @param string $field
     * @param array $value
     * @param string $conditionDeterminantType
     */
    public function __construct(string $field,
                                array $value,
                                string $conditionDeterminantType)
    {
        $this->type = ConditionTypeEnum::TERMS;
        $this->field = $field;
        $this->value = $value;
        $this->determinantType = $conditionDeterminantType;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function buildForRequest(): array
    {
        return [
            $this->field => $this->value,
        ];
    }
}
