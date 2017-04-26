<?php

namespace AlexBowers\GraphQl\Http\Controllers;

use AlexBowers\GraphQL\Schema;
use Illuminate\Support\Facades\Request;
use Youshido\GraphQL\Execution\Processor;

class GraphqlController
{
    protected $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function process(Request $request)
    {
        $query = $request->getContent();

        $config = config('graphql.schema');

        $config['query'] = $config['query'] ?? [];
        $config['mutation'] = $config['mutation'] ?? [];

        $schema = $this->schema->build($config);

        $processor = new Processor($schema);

        $processor->processPayload($query);

        return $processor->getResponseData();
    }
}