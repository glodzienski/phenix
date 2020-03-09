<?php

namespace glodzienski\AWSElasticsearchService\Conditions;
use glodzienski\AWSElasticsearchService\Enumerators\ConditionDeterminantTypeEnum;
use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;
use Illuminate\Support\Collection;

/**
 * Class ElasticSearchCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
abstract class ElasticSearchCondition
{
    /**
     * @var mixed
     */
    public $value;
    /**
     * @var string
     */
    public $field;
    /**
     * @var string
     */
    public $determinantType = ConditionDeterminantTypeEnum::MUST;
    /**
     * @var string
     */
    protected $type;

    /**
     * @return array
     */
    abstract public function buildForRequest(): array;

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getSintax(): string
    {
        $conditionsTypes = ConditionTypeEnum::all();

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
