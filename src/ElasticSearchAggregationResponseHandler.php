<?php

namespace glodzienski\AWSElasticsearchService;

use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchAggregation;

class ElasticSearchAggregationResponseHandler
{
    public static function treat(ElasticSearchAggregation $agg, array $aggregationsValues)
    {
        if ($agg->hasToIgnoreHandler()) {
            return 'ignored';
        }

        $treated = $agg->treatResponse($aggregationsValues);

        if ($agg->hasToHandleSubAggsHimself()) {
            return $treated;
        }

        if ($agg->hasChildren()) {
            foreach ($agg->getChildren() as $aggChild) {
                $treated[$aggChild->name] = self::treat($aggChild, $aggregationsValues[$aggChild->name]);
            }
        }

        return $treated;
    }

    public static function go($aggregationsSchema, $aggregationsValues)
    {
        $treatedResponse = [];

        foreach ($aggregationsSchema as $aggregation) {
            $treatedResponse[$aggregation->name] = self::treat($aggregation, $aggregationsValues[$aggregation->name]);
        }

        return $treatedResponse;
    }
}
