<?php

namespace glodzienski\AWSElasticsearchService\Helpers;

trait EnumTricks
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function all()
    {
        return (new \ReflectionClass(get_class()))->getConstants();
    }
}
