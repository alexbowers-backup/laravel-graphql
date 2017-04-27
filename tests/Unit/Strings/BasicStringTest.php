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
                    public $name = 'BasicString';

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

    /**
     * @test
     */
     function i_can_see_multiple_values_are_returned()
     {
         $request = '{ BasicString, MoreAdvancedString }';

         $schema = $this->schema->build([
             'query' => [
                 new class extends BaseQuery
                 {
                     public $name = 'BasicString';

                     function resolve($value, array $args, ResolveInfo $info)
                     {
                         return 'Hello World';
                     }
                 },
                 new class extends BaseQuery
                 {
                     public $name = 'MoreAdvancedString';

                     function resolve($value, array $args, ResolveInfo $info)
                     {
                         return 'Fizz Buzz';
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
                 'MoreAdvancedString' => 'Fizz Buzz',
             ]
         ];

         $this->assertEquals($expected, $response);
     }
}