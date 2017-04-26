<?php

namespace AlexBowers\GraphQl\Tests\Unit;

use AlexBowers\GraphQL\Schema;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * @var Schema $schema
     */
    protected $schema;

    public function setUp()
    {
        $this->schema = Container::getInstance()->make(Schema::class);
    }
}