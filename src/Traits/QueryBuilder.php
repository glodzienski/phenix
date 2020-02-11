<?php

namespace glodzienski\AWSElasticsearchService\Traits;

use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchAggregation;
use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchValueCountAggregation;
use glodzienski\AWSElasticsearchService\ElasticSearchAggregationResponseHandler;
use glodzienski\AWSElasticsearchService\ElasticSearchResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

/**
 * Trait QueryBuilder
 * @package glodzienski\AWSElasticsearchService\Traits
 */
trait QueryBuilder
{
    /**
     * @var array
     */
    private $wheres = [
        'must' => [],
        'must_not' => [],
        'should' => [],
    ];
    /**
     * @var array
     */
    private $columns = [];
    /**
     * @var array
     */
    private $aggregations = [];
    /**
     * @var
     */
    private $index;
    /**
     * @var string
     */
    private $type = 'doc';
    /**
     * @var
     */
    private $ordination;
    /**
     * @var
     */
    private $from;
    /**
     * @var
     */
    private $size;

    /**
     * @param string $index
     * @return QueryBuilder
     */
    public static function index(string $index)
    {
        $instance = (new static)->newQuery();
        if (method_exists($instance, 'validateIndex')) {
            $instance::validateIndex($index);
        }
        $instance->index = $index;

        return $instance;
    }

    /**
     * @return mixed
     */
    public static function getAvailableIndexes()
    {
        $client = new Client();

        $indexes = $client->get(config('elasticsearch.host') . "/_cat/indices?v&format=json")
            ->getBody()
            ->getContents();

        return json_decode($indexes, true);
    }

    /**
     * @return $this
     */
    public function newQuery()
    {
        return $this;
    }

    /**
     * @param $groupRequest
     * @param $field
     * @param $operator
     * @param $value
     */
    private function applyCommonWhere($groupRequest, $field, $operator, $value): void
    {
        switch ($operator) {
            case '=':
                $this->wheres[$groupRequest][] = [
                    'term' => [
                        $field => $value
                    ]
                ];
                break;
            case '>':
                $this->wheres[$groupRequest][] = [
                    'range' => [
                        $field => [
                            'gt' => $value
                        ]
                    ]
                ];
                break;
            case '<':
                $this->wheres[$groupRequest][] = [
                    'range' => [
                        $field => [
                            'lt' => $value
                        ]
                    ]
                ];
                break;
            case '>=':
                $this->wheres[$groupRequest][] = [
                    'range' => [
                        $field => [
                            'gte' => $value
                        ]
                    ]
                ];
                break;
            case '<=':
                $this->wheres[$groupRequest][] = [
                    'range' => [
                        $field => [
                            'lte' => $value
                        ]
                    ]
                ];
                break;
            case '!=':
            case '<>':
                $this->wheres['must_not'][] = [
                    'term' => [
                        $field => $value
                    ]
                ];
                break;
            case 'like':
                $this->wheres[$groupRequest][] = [
                    'match' => [
                        $field => $value
                    ]
                ];
                break;
        }
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function where($field, $value)
    {
        $args = func_get_args();
        if (count($args) == 3) {
            list($field, $operator, $value) = $args;
        } else {
            $operator = '=';
        }

        self::applyCommonWhere('must', $field, $operator, $value);

        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereIn($field, array $value)
    {
        $this->wheres['must'][] = [
            'terms' => [
                $field => $value
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereNotIn($field, array $value)
    {
        $this->wheres['must_not'][] = [
            'terms' => [
                $field => $value
            ]
        ];

        return $this;
    }

    /**
     * @param array $fields
     * @param string $value
     * @return $this
     */
    public function whereMultiMatch(array $fields, string $value)
    {
        $this->wheres['must'][] = [
            'multi_match' => [
                'query' => $value,
                'fields' => $fields
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereBetween($field, array $value)
    {
        $this->wheres['must'][] = [
            'range' => [
                $field => [
                    'gte' => $value[0],
                    'lte' => $value[1]
                ]
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereNotBetween($field, array $value)
    {
        $this->wheres['must_not'][] = [
            'range' => [
                $field => [
                    'gte' => $value[0],
                    'lte' => $value[1]
                ]
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function whereExists($field)
    {
        $this->wheres['must'][] = [
            'exists' => [
                'field' => $field
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function whereNotExists($field)
    {
        $this->wheres['must_not'][] = [
            'exists' => [
                'field' => $field
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @param string $flags
     * @return $this
     */
    public function whereRegexp($field, $value, $flags = 'ALL')
    {
        $this->wheres['must'][] = [
            'regexp' => [
                $field => [
                    'value' => $value,
                    'flags' => $flags
                ]
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param string $value
     * @return $this
     */
    public function wherePrefix($field, string $value)
    {
        $this->wheres['must'][] = [
            'prefix' => [
                $field => $value
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function orWhere($field, $value)
    {
        $args = func_get_args();
        if (count($args) == 3) {
            list($field, $operator, $value) = $args;
        } else {
            $operator = '=';
        }

        self::applyCommonWhere('should', $field, $operator, $value);

        return $this;
    }

    /**
     * @param array $fields
     * @param string $value
     * @return $this
     */
    public function orWhereMultiMatch(array $fields, string $value)
    {
        $this->wheres['should'][] = [
            'multi_match' => [
                'query' => $value,
                'fields' => $fields
            ]
        ];

        return $this;
    }

    /**
     * @param $field
     * @param string $value
     * @return $this
     */
    public function orWherePrefix($field, string $value)
    {
        $this->wheres['should'][] = [
            'prefix' => [
                $field => $value
            ]
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getWheres(): array
    {
        return $this->wheres;
    }

    /**
     * @return array
     */
    public function getAggregations(): array
    {
        return $this->aggregations;
    }

    /**
     * @return mixed
     */
    public function getOrdination()
    {
        return $this->ordination;
    }

    /**
     * @param $field
     * @param string $direction
     * @return $this
     */
    public function orderBy($field, $direction = 'asc')
    {
        $this->ordination[] = [
            $field => strtolower($direction) == 'asc' ? 'asc' : 'desc'
        ];
        return $this;
    }

    /**
     * @param int $perPage
     * @param array $columnsAliases
     * @param int $page
     * @param callable|null $toDoAfterSearch
     * @return array
     */
    public function paginate($perPage = 15, $columnsAliases = [], $page = 1, callable $toDoAfterSearch = null)
    {
        $firstRow = ($page == 1) ? 0 : ((($page - 1) * $perPage) + 1);
        $valueCount = new ElasticSearchValueCountAggregation('rowSums', 'id');

        $result = $this
            ->agg($valueCount)
            ->take($perPage)
            ->offset($firstRow)
            ->get();

        $totalRows = $result->getAgg('rowSums');
        $lastPage = ceil($totalRows / $perPage);

        $data = $result->getItems();

        if (!is_null($toDoAfterSearch)) {
            $data = $toDoAfterSearch($data, $result->getAggs());
        }
        if (!is_null($columnsAliases) && !empty($columnsAliases)) {
            $data = $this->applyPaginationAliases($columnsAliases, $data);
        }

        return [
            'current_page' => (int)$page,
            'data' => $data,
            'from' => (int)$page,
            'to' => (int)$perPage,
            'last_page' => (int)$lastPage,
            'per_page' => (int)$perPage,
            'total' => (int)$totalRows
        ];
    }

    /**
     * @return ElasticSearchResponse
     */
    public function get()
    {
        if ($this->getFrom() > 10000 || $this->getSize() > 10000) { //10000 é o limite padrão
            $this->setMaxRowsCanBeSearch();
        }

        $params = $this->buildParameters();
        $response = app('elasticsearch')->search(null, $params['body'], null, $this->getType(), $this->getIndex());

        if ($this->getFrom() > 10000 || $this->getSize() > 10000) { //10000 é o limite padrão
            $this->setMaxRowsCanBeSearch(10000);
        }

        return $this->treatResponse($response);
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from ?? 0;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size ?? 10;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param int|null $maxResult
     */
    private function setMaxRowsCanBeSearch(int $maxResult = null): void
    {
        $client = new Client();
        $index = $this->getIndex();
        $firstRow = $this->getFrom();
        $size = $this->getSize();

        $client->put(config('elasticsearch.host') . "/{$index}/_settings", [
            'json' => [
                'max_result_window' => $maxResult ?? ($firstRow + $size + 1)
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return array
     */
    private function buildParameters()
    {
        $params = [
            'body' => []
        ];

        $params['index'] = $this->index;
        $params['type'] = $this->type;

        if (!empty($this->wheres)) {
            $params['body']['query']['bool'] = $this->wheres;
        }
        if (!empty($this->aggregations)) {
            foreach ($this->aggregations as $agg) {
                $params['body']['aggs'][$agg->name] = $this->buildAggregationsForRequest($agg);
            }
        }
        if (!empty($this->ordination)) {
            $params['body']['sort'] = $this->getOrdination();
        }
        if (isset($this->size)) {
            $params['body']['size'] = (string)$this->getSize();
        }
        if (isset($this->from)) {
            $params['body']['from'] = $this->getFrom();
        }
        if (!empty($this->columns)) {
            $params['body']['_source'] = $this->getColumns();
        }

        return $params;
    }

    /**
     * @param ElasticSearchAggregation $agg
     * @return array
     */
    private function buildAggregationsForRequest(ElasticSearchAggregation $agg)
    {
        $parameters = $agg->buildForRequest();
        if ($agg->hasChildren()) {
            foreach ($agg->getChildren() as $aggChild) {
                $parameters['aggs'][$aggChild->name] = $this->buildAggregationsForRequest($aggChild);
            }
        }

        return $parameters;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param $response
     * @return ElasticSearchResponse
     */
    private function treatResponse($response): ElasticSearchResponse
    {
        $items = collect();

        if (is_null($response)) {
            return new ElasticSearchResponse($items);
        }

        $hits = $response['hits']['hits'];
        foreach ($hits as $hit) {
            $items->push(collect($hit['_source']));
        }

        $aggs = $response['aggregations'] ?? [];
        $aggregations = ElasticSearchAggregationResponseHandler::go($this->aggregations, $aggs);

        return new ElasticSearchResponse($items, collect($aggregations));
    }

    /**
     * @param int $row
     * @return $this
     */
    public function offset(int $row = 0)
    {
        $this->from = $row;

        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function take(int $quantity = 10)
    {
        $this->size = $quantity;

        return $this;
    }

    /**
     * @param array $columnsAliases
     * @param Collection $items
     * @return mixed
     */
    private function applyPaginationAliases(array $columnsAliases, Collection $items)
    {
        return $items
            ->transform(function (Collection $item) use ($columnsAliases) {
                $treatedItem = [];
                foreach ($columnsAliases as $key => $alias) {
                    if ($item->has($key)) {
                        $treatedItem[$alias] = $item->get($key);
                    }
                }

                return $treatedItem;
            })
            ->filter();
    }

    /**
     * @param ElasticSearchAggregation $aggregation
     * @return $this
     */
    public function agg(ElasticSearchAggregation $aggregation)
    {
        $this->aggregations[] = $aggregation;

        return $this;
    }

    /**
     * @param string $sentence
     * @return array
     */
    public function getMainWords(string $sentence): array
    {
        $words = $this->analyzer('autosearch', $sentence);

        return collect($words['tokens'])
            ->pluck('token')
            ->toArray();
    }

    /**
     * @param string $name
     * @param string $toAnalizy
     * @return mixed
     */
    public function analyzer(string $name, string $toAnalizy)
    {
        $client = new Client();
        $index = $this->getIndex();

        $response = $client->get("http://" . config('elasticsearch.host') . "/{$index}/_analyze", [
            'json' => [
                'analyzer' => $name,
                'text' => $toAnalizy
            ]
        ]);
        return collect(json_decode($response->getBody()->getContents(), true));
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        $params = $this->buildParameters();

        return $params['body'];
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function select(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }
}
