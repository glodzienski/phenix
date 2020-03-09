<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionTypeEnum;

/**
 * Class ElasticSearchMatchCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchMatchCondition extends ElasticSearchCondition
{
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
        $this->type = ElasticSearchConditionTypeEnum::TERM;
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

    /**
     * @param array $values
     * @return mixed
     */
    public function treatResponse(array $values)
    {
        return $values['value'];
    }
}
