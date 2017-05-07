<?php namespace AlexBowers\GraphQL\Processors;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQL\Processor;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\ObjectType as GraphQLObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class ObjectType
{
    protected $name;

    /**
     * @var BaseQuery $class
     */
    protected $class;

    protected $processor;

    public function process($name, $class, Processor $processor)
    {
        $this->name = $name;
        $this->class = $class;
        $this->processor = $processor;

        return [
            'type' => $this->processType(),
            'args' => $this->processArgs(),
            'resolve' => $this->processResolve(),
        ];
    }

    protected function processType()
    {
        return new GraphQLObjectType([
            'name' => $this->name,
            'fields' => $this->processFields(),
        ]);
    }

    protected function processArgs()
    {
        return collect($this->class->args())->transform(function ($type) {
            return $this->parseType($type);
        })->toArray();
    }

    protected function processFields()
    {
        return collect($this->class->fields())->map(function($type) {
            return $this->parseType($type);
        })->filter()->toArray();
    }

    protected function parseType($type)
    {
        $optional = str_contains($type, ['optional', 'nullable']);

        $type = str_replace(['optional', 'nullable'], '', $type);

        $type = trim($type);

        $type = $this->getTypeObject($type);

        if ($optional) {
            return $type;
        }

        return new NonNullType($type);
    }

    protected function getTypeObject($type)
    {
        switch ($type) {
            case 'string':
            case 'text':
                return new StringType;
                break;
            case 'integer':
                return new IntType;
                break;
            case 'boolean':
                return new BooleanType;
                break;
            case 'float':
                return new FloatType;
                break;
        }

        throw new \Exception("Unsupported Type {$type}");
    }

    protected function processResolve()
    {
        $class = $this->class;

        return function ($value, array $args, ResolveInfo $info) use ($class) {
            return $class->resolve($value, $args, $info);
        };
    }

}