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
    private $aggs;
    /**
     * @var string
     */
    private $scroll;

    /**
     * ElasticSearchResponse constructor.
     * @param Collection $items
     * @param Collection|null $aggs
     */
    public function __construct(Collection $items, Collection $aggs = null)
    {
        $this->items = $items;
        $this->aggs = $aggs;
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
    public function getAggs()
    {
        return $this->aggs;
    }

    /**
     * @param string $aggName
     * @return mixed
     */
    public function getAgg(string $aggName)
    {
        if (isset($this->aggs) && $this->aggs->has($aggName)) {
            return $this->aggs->get($aggName);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getScroll(): string
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
}
