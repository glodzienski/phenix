<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use glodzienski\AWSElasticsearchService\Enumerators\ElasticSearchAggregationTypeEnum;
use Illuminate\Support\Collection;

/**
 * Class ElasticSearchAggregation
 * @package glodzienski\AWSElasticsearchService\Aggregations
 */
abstract class ElasticSearchAggregation
{
    /**
     * Propriedades para funcionamento em comum de todas as agregações
     * */

    public $name;
    /**
     * @var
     */
    public $value;
    /**
     * @var bool
     */
    protected $ignoreHandler = false; // Esse valor está presente na classe ElasticSearchAggregationTypeEnum
    /**
     * @var bool
     */
    protected $handleSubAggsHimself = false;// Existem agregação que necessitam lidar elas mesmas com suas subagregações
    /**
     * @var
     */
    protected $type;
    /**
     * @var
     */
    protected $parentAggregation;
    /**
     * @var array
     */
    protected $childrenAggregations = [];

    /**
     * Declarações de métodos obrigatórios para implementação quando essa classe é herdada.
     * */

    abstract public function buildForRequest(): array;

    /**
     * @param array $values
     * @return mixed
     */
    abstract public function treatResponse(array $values);

    /**
     * Métodos de vínculo entre agregações
     * */

    public function getParent(): ElasticSearchAggregation
    {
        return $this->parentAggregation;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return collect($this->childrenAggregations);
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->childrenAggregations);
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return !empty($this->parentAggregation);
    }

    /**
     * @param ElasticSearchAggregation $aggregation
     * @return $this
     */
    public function bindSub(ElasticSearchAggregation $aggregation)
    {
        $aggregation->setParent($aggregation);
        $this->setChild($aggregation);

        return $this;
    }

    /**
     * @param ElasticSearchAggregation $aggregation
     * @return $this
     */
    public function setParent(ElasticSearchAggregation $aggregation)
    {
        $this->parentAggregation = $aggregation;

        return $this;
    }

    /**
     * @param ElasticSearchAggregation $aggregation
     * @return $this
     */
    public function setChild(ElasticSearchAggregation $aggregation)
    {
        $this->childrenAggregations[] = $aggregation;

        return $this;
    }

    /**
     * Métodos que facilitam a utilização das agregações
     * */

    /**
     * @return string
     */
    public function getSyntaxOfAggregation(): string
    {
        $aggregationsTypes = ElasticSearchAggregationTypeEnum::all();

        return strtolower(array_flip($aggregationsTypes)[$this->type]);
    }

    /**
     * @param bool $ignoreHandler
     * @return $this
     */
    public function ignoreHandler(bool $ignoreHandler = true)
    {
        $this->ignoreHandler = $ignoreHandler;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasToIgnoreHandler(): bool
    {
        return $this->ignoreHandler;
    }

    /**
     * @return bool
     */
    public function hasToHandleSubAggsHimself(): bool
    {
        return $this->handleSubAggsHimself;
    }
}
