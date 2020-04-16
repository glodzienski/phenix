<?php

namespace glodzienski\AWSElasticsearchService\Conditions;

use glodzienski\AWSElasticsearchService\Enumerators\ConditionTypeEnum;

/**
 * Class ElasticSearchMatchPhraseCondition
 * @package glodzienski\AWSElasticsearchService\Conditions
 */
class ElasticSearchMatchPhraseCondition extends ElasticSearchCondition
{
    /**
     * @var string
     */
    private $analyzer;

    /**
     * @param string $analyzer
     * @return ElasticSearchMatchPhraseCondition
     */
    public function analyzer(string $analyzer): ElasticSearchMatchPhraseCondition
    {
        $this->analyzer = $analyzer;

        return $this;
    }

    /**
     * ElasticSearchMatchPhraseCondition constructor.
     * @param string $field
     * @param string $value
     */
    public function __construct(string $field, string $value)
    {
        $this->type = ConditionTypeEnum::MATCH_PHRASE;
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function buildForRequest(): array
    {
        $conditionStructure = [
            'query' => $this->value,
        ];

        if (!empty($this->analyzer)) {
            $conditionStructure['analyzer'] = $this->analyzer;
        }

        return [
            $this->field => $conditionStructure
        ];
    }
}
