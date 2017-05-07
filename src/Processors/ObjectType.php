<?php namespace AlexBowers\GraphQL\Processors;

use AlexBowers\GraphQL\BaseQuery;
use AlexBowers\GraphQL\Processor;
use AlexBowers\GraphQL\Support\ProcessorAssistant;
use Illuminate\Container\Container;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Object\ObjectType as GraphQLObjectType;

class ObjectType
{
    use ProcessorAssistant;

    protected $name;

    /**
     * @var BaseQuery $class
     */
    protected $class;

    /**
     * @var Processor $processor
     */
    protected $processor;

    /**
     * @var Container $app
     */
    protected $app;

    public function process($name, $class, Processor $processor)
    {
        $this->name = $name;
        $this->class = $class;
        $this->processor = $processor;
        $this->app = Container::getInstance();

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
        return collect($this->class->fields())->map(function ($type, $name) {
            if (is_object($type)) {
                return $this->processor->process($name, $type);
            } else if (is_string($type) && class_exists($type)) {
                $class = $this->app->make($type);

                if (is_numeric($name)) {
                    if (!is_null($class->name)) {
                        $name = $class->name;
                    } else {
                        $name = class_basename($class);
                    }
                }

                $this->processor->process($name, $class);
            }

            return $this->parseType($type);
        })->filter()->toArray();
    }

    protected function processResolve()
    {
        $class = $this->class;

        return function ($value, array $args, ResolveInfo $info) use ($class) {
            return $class->resolve($value, $args, $info);
        };
    }
}