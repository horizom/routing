<?php

namespace Horizom\Routing\HandlerResolvers;

use Closure;
use Horizom\Routing\Exceptions\WrongRouteHandlerException;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;

trait RouteHandlerResolverTrait
{
    /**
     * @param Closure $normalizedHandler
     * @param Closure|callable|string|string[] $rawHandler
     * @throws ReflectionException
     */
    protected function validateReturnType(Closure $normalizedHandler, $rawHandler): void
    {
        $reflectionFunction = new ReflectionFunction($normalizedHandler);
        $returnType = $reflectionFunction->getReturnType();
        if (null === $returnType || $returnType->allowsNull()) {
            throw WrongRouteHandlerException::forWrongReturnType($rawHandler);
        }

        if (!$returnType instanceof ReflectionNamedType) {
            throw WrongRouteHandlerException::forWrongReturnType($rawHandler);
        }

        /** @var class-string $typeName */
        $typeName = $returnType->getName();
        if ($typeName === ResponseInterface::class) {
            return;
        }

        try {
            $reflectionClass = new ReflectionClass($typeName);
        } catch (ReflectionException $e) {
            throw WrongRouteHandlerException::forWrongReturnType($rawHandler);
        }

        if (!$reflectionClass->implementsInterface(ResponseInterface::class)) {
            throw WrongRouteHandlerException::forWrongReturnType($rawHandler);
        }
    }
}
