<?php

namespace AlexBowers\GraphQl\Tests\Unit\Strings;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQl\Tests\Unit\BaseTestCase;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Execution\ResolveInfo;

class BasicStringArgsTest extends BaseTestCase
{
    /**
     * @test
     */
    function one_required_integer_argument()
    {
        $request = '{ BasicString(length: 3) }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'BasicString';

                    function args()
                    {
                        return [
                            'length' => 'integer',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        return str_limit('Hello World', $args['length']);
                    }
                },
            ],
        ]);

        $processor = new Processor($schema);
        $processor->processPayload($request);

        $response = $processor->getResponseData();

        $expected = [
            'data' => [
                'BasicString' => 'Hel...',
            ]
        ];

        $this->assertEquals($expected, $response);
    }

    /**
     * @test
     */
    function two_required_integer_argument()
    {
        $request = '{ BasicString(length: 3, offset: 1) }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'BasicString';

                    function args()
                    {
                        return [
                            'length' => 'integer',
                            'offset' => 'integer',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        return substr("Hello World", $args['offset'], $args['length']);
                    }
                },
            ],
        ]);

        $processor = new Processor($schema);
        $processor->processPayload($request);

        $response = $processor->getResponseData();

        $expected = [
            'data' => [
                'BasicString' => 'ell',
            ]
        ];

        $this->assertEquals($expected, $response);
    }

    /**
     * @test
     */
     function one_required_boolean_argument()
     {
         $request = '{ capitalised: BasicString(capitalise: true), noncapitalised: BasicString(capitalise: false) }';

         $schema = $this->schema->build([
             'query' => [
                 new class extends BaseQuery
                 {
                     public $name = 'BasicString';

                     function args()
                     {
                         return [
                             'capitalise' => 'boolean',
                         ];
                     }

                     function resolve($value, array $args, ResolveInfo $info)
                     {
                         if ($args['capitalise']) {
                             return strtoupper('Hello World');
                         }

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
                 'capitalised' => 'HELLO WORLD',
                 'noncapitalised' => 'Hello World',
             ]
         ];

         $this->assertEquals($expected, $response);
     }

    /**
     * @test
     */
    function one_optional_integer_argument()
    {
        $request = '{ BasicString }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'BasicString';

                    function args()
                    {
                        return [
                            'length' => 'optional integer',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        if (isset($args['length'])) {
                            return str_limit('Hello World', $args['length']);
                        }

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
    function one_nullable_integer_argument()
    {
        $request = '{ BasicString }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'BasicString';

                    function args()
                    {
                        return [
                            'length' => 'nullable integer',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        if (isset($args['length'])) {
                            return str_limit('Hello World', $args['length']);
                        }

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
    function one_required_float_argument()
    {
        $request = '{ CelciusToKelvin(temperature: 32.59) }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'CelciusToKelvin';

                    function args()
                    {
                        return [
                            'temperature' => 'float',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        return ($args['temperature'] + 273.15) . " Kelvin";
                    }
                },
            ],
        ]);

        $processor = new Processor($schema);
        $processor->processPayload($request);

        $response = $processor->getResponseData();

        $expected = [
            'data' => [
                'CelciusToKelvin' => "305.74 Kelvin",
            ]
        ];

        $this->assertEquals($expected, $response);
    }
}