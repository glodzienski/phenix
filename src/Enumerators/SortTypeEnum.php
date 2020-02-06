<?php

namespace glodzienski\AWSElasticsearchService\Enumerators;

use glodzienski\AWSElasticsearchService\Helpers\EnumTricks;

class SortTypeEnum
{
    use EnumTricks;

    public const ASC = 0;
    public const DESC = 1;
}
