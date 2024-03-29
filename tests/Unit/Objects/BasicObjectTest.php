<?php

namespace AlexBowers\GraphQl\Tests\Unit\Objects;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQl\Tests\Unit\BaseTestCase;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Execution\ResolveInfo;

class BasicObjectTest extends BaseTestCase
{
    /**
     * @test
     */
    function i_can_see_a_basic_object()
    {
        $request = '{ BasicObject { FirstName, LastName, Age } }';

        $schema = $this->schema->build([
            'query' => [
                new class extends BaseQuery
                {
                    public $name = 'BasicObject';

                    public $type = 'object';

                    public function fields()
                    {
                        return [
                            'FirstName' => 'string',
                            'LastName' => 'string',
                            'Age' => 'integer',
                        ];
                    }

                    function resolve($value, array $args, ResolveInfo $info)
                    {
                        return [
                            'FirstName' => 'Alex',
                            'LastName' => 'Bowers',
                            'Age' => 22,
                        ];
                    }
                },
            ],
        ]);

        $processor = new Processor($schema);
        $processor->processPayload($request);

        $response = $processor->getResponseData();

        $expected = [
            'data' => [
                'BasicObject' => [
                    'FirstName' => 'Alex',
                    'LastName' => 'Bowers',
                    'Age' => 22,
                ],
            ],
        ];

        $this->assertEquals($expected, $response);
    }

    /**
     * @test
     */
     function i_can_get_a_specific_user_object()
     {
         $request = '{ author: User(id: 2) { FirstName, LastName }, commenter: User(id: 3) { FirstName, LastName} }';

         $schema = $this->schema->build([
             'query' => [
                 new class extends BaseQuery
                 {
                     public $name = 'User';

                     public $type = 'object';

                     public function args()
                     {
                         return [
                             'id' => 'integer',
                         ];
                     }

                     public function fields()
                     {
                         return [
                             'FirstName' => 'string',
                             'LastName' => 'string',
                         ];
                     }

                     function resolve($value, array $args, ResolveInfo $info)
                     {
                         if ($args['id'] == 1) {
                             return [
                                 'FirstName' => 'Jeffrey',
                                 'LastName' => 'Way',
                             ];
                         } else if ($args['id'] == 2) {
                             return [
                                 'FirstName' => 'Taylor',
                                 'LastName' => 'Otwell',
                             ];
                         } else {
                             return [
                                 'FirstName' => 'Alex',
                                 'LastName' => 'Bowers',
                             ];
                         }
                     }
                 },
             ],
         ]);

         $processor = new Processor($schema);
         $processor->processPayload($request);

         $response = $processor->getResponseData();

         $expected = [
             'data' => [
                 'author' => [
                     'FirstName' => 'Taylor',
                     'LastName' => 'Otwell',
                 ],
                 'commenter' => [
                     'FirstName' => 'Alex',
                     'LastName' => 'Bowers',
                 ],
             ],
         ];

         $this->assertEquals($expected, $response);
     }

     /**
      * @test
      */
      function an_object_field_can_have_arguments()
      {
          $request = '{ Post(id: 2) { title, author, description(length: 5) } }';

          $schema = $this->schema->build([
              'query' => [
                  new class extends BaseQuery
                  {
                      public $name = 'Post';

                      public $type = 'object';

                      public function args()
                      {
                          return [
                              'id' => 'integer',
                          ];
                      }

                      public function fields()
                      {
                          return [
                              'title' => 'string',
                              'author' => 'string',
                              'description' => new class extends BaseQuery
                              {

                                  public function args()
                                  {
                                      return [
                                          'length' => 'nullable integer',
                                      ];
                                  }

                                  function resolve($value, array $args, ResolveInfo $info)
                                  {
                                      if (isset($args['length'])) {
                                          return str_limit('Hello World', $args['length']);
                                      } else {
                                          return 'Hello World';
                                      }
                                  }
                              },
                          ];
                      }

                      function resolve($value, array $args, ResolveInfo $info)
                      {
                          return [
                              'title' => 'An amazing blog post',
                              'author' => 'Alex Bowers',
                          ];
                      }
                  },
              ],
          ]);

          $processor = new Processor($schema);
          $processor->processPayload($request);

          $response = $processor->getResponseData();

          $expected = [
              'data' => [
                  'Post' => [
                      'title' => 'An amazing blog post',
                      'author' => 'Alex Bowers',
                      'description' => 'Hello...',
                  ],
              ],
          ];

          $this->assertEquals($expected, $response);
      }
}