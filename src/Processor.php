<?php

namespace AlexBowers\GraphQL;

use AlexBowers\GraphQL\Processors\ObjectType;
use AlexBowers\GraphQL\Processors\StringType;
use Illuminate\Container\Container;

class Processor
{

    protected $string;
    protected $app;

    public function __construct(StringType $string, ObjectType $object)
    {
        $this->string = $string;
        $this->object = $object;
        $this->app = Container::getInstance();
    }

    public function process($name, $class)
    {
        switch ($class->type) {
            case 'string':
                return $this->handleString($name, $class);
                break;
            case 'object':
                return $this->handleObject($name, $class);
                break;
        }

        throw new \Exception("Unsure how to handle type {$class->type}");
    }

    public function handleString($name, $class)
    {
        return $this->string->process(
            $name,
            $class,
            $this->app->make(Processor::class)
        );
    }

    public function handleObject($name, $class)
    {
        return $this->object->process(
            $name,
            $class,
            $this->app->make(Processor::class)
        );
    }
}