<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionDeterminantTypeEnum;
use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchConditionTypeEnum;
use Illuminate\Support\Collection;

/**
 * Class ElasticSearchCondition
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
abstract class ElasticSearchCondition
{
    /**
     * Propriedades para funcionamento em comum de todas as condições
     * */

    public $value;
    public $field;
    public $determinantType = ElasticSearchConditionDeterminantTypeEnum::MUST;
    protected $type;

    abstract public function buildForRequest(): array;

    abstract public function treatResponse(array $values);

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getSintax(): string
    {
        $conditionsTypes = ElasticSearchConditionTypeEnum::all();

        return strtolower(array_flip($conditionsTypes)[$this->type]);
    }

    /**
     * @return string
     */
    public function getDeterminantType(): string
    {
        return $this->determinantType;
    }

    /**
     * @param string $determinantType
     */
    public function setDeterminantType(string $determinantType): void
    {
        $this->determinantType = $determinantType;
    }
}
