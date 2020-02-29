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
    private $sources;
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
     * @param Collection|null $sources
     * @param Collection|null $aggregations
     */
    public function __construct(?Collection $sources, ?Collection $aggregations)
    {
        $this->sources = $sources ?? collect();
        $this->aggregations = $aggregations ?? collect();
        $this->scrollHasMissedTheCache = false;
        $this->totalHits = 0;
    }

    /**
     * @param Collection $sources
     */
    public function setSources(Collection $sources): void
    {
        $this->sources = $sources;
    }

    /**
     * @param Collection|null $aggregations
     */
    public function setAggregations(?Collection $aggregations): void
    {
        $this->aggregations = $aggregations;
    }

    /**
     * @return mixed
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    /**
     * @return Collection
     */
    public function getAggregations(): Collection
    {
        return $this->aggregations;
    }

    /**
     * @param string $aggregationName
     * @return mixed
     */
    public function getAggregation(string $aggregationName)
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
