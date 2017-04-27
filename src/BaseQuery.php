<?php namespace AlexBowers\GraphQL;

use Youshido\GraphQL\Execution\ResolveInfo;

abstract class BaseQuery
{
    public $name = null;

    public $type = 'string';

    public function args()
    {
        return [];
    }

    public function fields()
    {
        return [];
    }

    abstract function resolve($value, array $args, ResolveInfo $info);
}