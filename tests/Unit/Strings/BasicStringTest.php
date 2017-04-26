<?php

namespace AlexBowers\GraphQl\Tests\Unit\Strings;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQl\Tests\Unit\BaseTestCase;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Execution\ResolveInfo;

class BasicStringTest extends BaseTestCase
{
    /**
     * @test
     */
    function i_can_see_a_basic_string_is_returned()
    {
        $request = '{ BasicString }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        return 'Hello World';
                    }
                },
            ],
        ]);

        $processor = new Processor($schema);
        $processor->processPayload($request);

        $response = $processor->getResponseData();

        $expected = [
            'data' => [
                'BasicString' => 'Hello World',
            ]
        ];

        $this->assertEquals($expected, $response);
    }
}