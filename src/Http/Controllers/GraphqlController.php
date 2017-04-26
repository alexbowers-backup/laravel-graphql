<?php

namespace AlexBowers\GraphQl\Http\Controllers;

use AlexBowers\GraphQL\Schema;
use Illuminate\Support\Facades\Request;

class GraphqlController
{
    protected $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function process(Request $request)
    {
        // TODO: Make it work
    }
}