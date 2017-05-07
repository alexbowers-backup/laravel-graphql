<?php namespace AlexBowers\GraphQL\Support;


use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

trait ProcessorAssistant
{
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
}