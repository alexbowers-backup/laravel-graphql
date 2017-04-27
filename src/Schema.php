<?php

namespace AlexBowers\GraphQL;

use AlexBowers\GraphQL\Builders\QueryBuilder;
use Youshido\GraphQL\Schema\Schema as GraphQLSchema;

class Schema
{
    protected $query;

    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function build(array $schema)
    {
        $query = $this->query->build($schema['query']);

        return new GraphQLSchema([
            'query' => $query,
            'mutation' => [],
        ]);
    }
}