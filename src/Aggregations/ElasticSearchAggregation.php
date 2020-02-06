<?php

namespace glodzienski\AWSElasticsearchService\Aggregations;

use App\ElasticSearch\ElasticSearchAggregationTypeEnum;
use Illuminate\Support\Collection;

abstract class ElasticSearchAggregation
{
    /**
     * Propriedades para funcionamento em comum de todas as agregações
     * */

    public $name;
    public $value;
    protected $ignoreHandler = false; // Esse valor está presente na classe ElasticSearchAggregationTypeEnum
    protected $handleSubAggsHimself = false;// Existem agregação que necessitam lidar elas mesmas com suas subagregações
    protected $type;
    protected $parentAggregation;
    protected $childrenAggregations = [];

    /**
     * Declarações de métodos obrigatórios para implementação quando essa classe é herdada.
     * */

    abstract public function buildForRequest(): array;

    abstract public function treatResponse(array $values);

    /**
     * Métodos de vínculo entre agregações
     * */

    public function getParent(): ElasticSearchAggregation
    {
        return $this->parentAggregation;
    }

    public function getChildren(): Collection
    {
        return collect($this->childrenAggregations);
    }

    public function hasChildren(): bool
    {
        return !empty($this->childrenAggregations);
    }

    public function hasParent(): bool
    {
        return !empty($this->parentAggregation);
    }

    public function bindSub(ElasticSearchAggregation $aggregation)
    {
        $aggregation->setParent($aggregation);
        $this->setChild($aggregation);

        return $this;
    }

    public function setParent(ElasticSearchAggregation $aggregation): void
    {
        $this->parentAggregation = $aggregation;
    }

    public function setChild(ElasticSearchAggregation $aggregation): void
    {
        $this->childrenAggregations[] = $aggregation;
    }

    /**
     * Métodos que facilitam a utilização das agregações
     * */

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getSintaxOfAggregation(): string
    {
        $aggregationsTypes = ElasticSearchAggregationTypeEnum::all();

        return strtolower(array_flip($aggregationsTypes)[$this->type]);
    }

    public function ignoreHandler(bool $ignoreHandler = true)
    {
        $this->ignoreHandler = $ignoreHandler;

        return $this;
    }

    public function hasToIgnoreHandler(): bool
    {
        return $this->ignoreHandler;
    }

    public function hasToHandleSubAggsHimself(): bool
    {
        return $this->handleSubAggsHimself;
    }
}
