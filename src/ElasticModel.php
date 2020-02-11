<?php

namespace glodzienski\AWSElasticsearchService;

use glodzienski\AWSElasticsearchService\Traits\QueryBuilder;

/**
 * Class ElasticModel
 * @package glodzienski\AWSElasticsearchService
 */
abstract class ElasticModel
{
    use QueryBuilder;
}
