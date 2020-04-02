<?php

namespace glodzienski\AWSElasticsearchService\Functionalities;

/**
 * Trait SortFunctionality
 * @package glodzienski\AWSElasticsearchService\Functionalities
 */
trait SortFunctionality
{
    /**
     * @var string
     */
    public $sortBy;
    /**
     * @var string
     */
    public $sortType;

    /**
     * @param string $by
     * @param string $type
     * @return $this
     */
    public function sort(string $by = '', string $type = 'asc'): self
    {
        $this->sortBy = $by;
        $this->sortType = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSorter(): bool
    {
        return isset($this->sortBy) && isset($this->sortType);
    }
}
