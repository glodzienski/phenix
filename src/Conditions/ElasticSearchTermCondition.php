<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionTypeEnum;

/**
 * Class ElasticSearchTermCondition
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
class ElasticSearchTermCondition extends ElasticSearchCondition
{
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
