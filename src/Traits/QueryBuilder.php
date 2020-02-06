<?php

namespace glodzienski\AWSElasticsearchService\Traits;

use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchAggregation;
use glodzienski\AWSElasticsearchService\Aggregations\ElasticSearchValueCountAggregation;
use glodzienski\AWSElasticsearchService\ElasticSearchAggregationResponseHandler;
use glodzienski\AWSElasticsearchService\ElasticSearchResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

trait QueryBuilder
{
    private $wheres = [
        'must' => [],
        'must_not' => [],
        'should' => [],
    ];
    private $aggregations = [];
    private $index;
    private $type = 'doc';
    private $ordination;
    private $from;
    private $size;

    public static function index(string $index)
    {
        $instance = (new static)->newQuery();
        if (method_exists($instance, 'validateIndex')) {
            $instance::validateIndex($index);
        }
        $instance->index = $index;

        return $instance;
    }

    public static function getAvailableIndexes()
    {
        $client = new Client();

        $indexes = $client->get(config('elasticsearch.host') . "/_cat/indices?v&format=json")
            ->getBody()
            ->getContents();

        return json_decode($indexes, true);
    }

    public function newQuery()
    {
        return $this;
    }

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

    public function whereIn($field, array $value)
    {
        $this->wheres['must'][] = [
            'terms' => [
                $field => $value
            ]
        ];

        return $this;
    }

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

    public function whereNotIn($field, array $value)
    {
        $this->wheres['must_not'][] = [
            'terms' => [
                $field => $value
            ]
        ];

        return $this;
    }

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

    public function getWheres(): array
    {
        return $this->wheres;
    }

    public function getAggregations(): array
    {
        return $this->aggregations;
    }

    public function getOrdination()
    {
        return $this->ordination;
    }

    public function whereExists($field)
    {
        $this->wheres['must'][] = [
            'exists' => [
                'field' => $field
            ]
        ];

        return $this;
    }

    public function whereNotExists($field)
    {
        $this->wheres['must_not'][] = [
            'exists' => [
                'field' => $field
            ]
        ];

        return $this;
    }

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

    public function orderBy($field, $direction = 'asc')
    {
        $this->ordination[] = [
            $field => strtolower($direction) == 'asc' ? 'asc' : 'desc'
        ];
        return $this;
    }

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

    public function getFrom(): int
    {
        return $this->from ?? 0;
    }

    public function getSize(): int
    {
        return $this->size ?? 10;
    }

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

    public function getIndex()
    {
        return $this->index;
    }

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

        return $params;
    }

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

    public function getType(): string
    {
        return $this->type;
    }

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

    public function offset(int $row = 0)
    {
        $this->from = $row;

        return $this;
    }

    public function take(int $quantity = 10)
    {
        $this->size = $quantity;

        return $this;
    }

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

    public function agg(ElasticSearchAggregation $aggregation)
    {
        $this->aggregations[] = $aggregation;

        return $this;
    }

    public function getMainWords(string $sentence): array
    {
        $words = $this->analyzer('autosearch', $sentence);

        return collect($words['tokens'])
            ->pluck('token')
            ->toArray();
    }

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

    public function getPayload(): array
    {
        $params = $this->buildParameters();

        return $params['body'];
    }
}
