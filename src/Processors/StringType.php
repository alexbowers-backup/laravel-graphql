<?php namespace AlexBowers\GraphQL\Processors;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQL\Processor;
use AlexBowers\GraphQL\Support\ProcessorAssistant;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType as GraphQLStringType;

class StringType
{
    use ProcessorAssistant;

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
        return new GraphQLStringType();
    }

    protected function processArgs()
    {
        return collect($this->class->args())->transform(function ($type) {
            return $this->parseType($type);
        })->toArray();
    }

    protected function processResolve()
    {
        $class = $this->class;

        return function ($value, array $args, ResolveInfo $info) use ($class) {
            return $class->resolve($value, $args, $info);
        };
    }
}