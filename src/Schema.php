<?php

namespace AlexBowers\GraphQL;

use Youshido\GraphQL\Schema\Schema as GraphQLSchema;

class Schema
{
    public function build(array $schema)
    {
        // TODO: Convert the schema to Youshido's format

        return new GraphQLSchema([
            'query' => [],
            'mutation' => [],
        ]);
    }
}