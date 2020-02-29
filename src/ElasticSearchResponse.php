<?php

namespace glodzienski\AWSElasticsearchService;

use Illuminate\Support\Collection;

/**
 * Class ElasticSearchResponse
 * @package glodzienski\AWSElasticsearchService
 */
class ElasticSearchResponse
{
    /**
     * @var Collection
     */
    private $items;
    /**
     * @var Collection|null
     */
    private $aggregations;
    /**
     * @var int
     */
    private $totalHits;
    /**
     * @var string
     */
    private $scroll;
    /**
     * @var bool
     */
    public $scrollHasMissedTheCache;

    /**
     * ElasticSearchResponse constructor.
     * @param Collection $items
     * @param Collection|null $aggregations
     */
    public function __construct(Collection $items, Collection $aggregations = null)
    {
        $this->items = $items;
        $this->aggregations = $aggregations;
        $this->scrollHasMissedTheCache = false;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param string $aggregationName
     * @return mixed
     */
    public function getAgg(string $aggregationName)
    {
        if (isset($this->aggregations) && $this->aggregations->has($aggregationName)) {
            return $this->aggregations->get($aggregationName);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getScroll()
    {
        return $this->scroll;
    }

    /**
     * @param string $scroll
     */
    public function setScroll(string $scroll): void
    {
        $this->scroll = $scroll;
    }

    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return $this->totalHits;
    }

    /**
     * @param int $totalHits
     */
    public function setTotalHits(int $totalHits): void
    {
        $this->totalHits = $totalHits;
    }


}
