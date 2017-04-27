<?php

namespace AlexBowers\GraphQL\Builders;

use AlexBowers\GraphQL\Processor;
use Illuminate\Container\Container;
use Youshido\GraphQL\Introspection\QueryType;

class QueryBuilder
{
    protected $processor;

    /**
     * @var Container $app
     */
    protected $app;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
        $this->app = Container::getInstance();
    }

    public function build($query)
    {
        return new QueryType([
            'name' => 'RootQueryType',
            'fields' => $this->process($query),
        ]);
    }

    private function process($query)
    {
        $response = [];

        foreach ($query as $name => $statement) {
            if (is_object($statement)) {
                $response[$statement->name] = $this->processor->process($statement->name, $statement);
            }
        }

        return $response;
    }

}