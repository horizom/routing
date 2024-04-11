<?php

namespace Horizom\Routing\HandlerResolvers;

use Closure;
use function explode;
use function is_string;
use function strpos;

use Horizom\Routing\Exceptions\WrongRouteHandlerException;
use Horizom\Routing\Interfaces\RouteHandlerResolverInterface;
use Invoker\CallableResolver;
use Invoker\Exception\NotCallableException;
use ReflectionException;

class PhpDiRouteHandlerResolver implements RouteHandlerResolverInterface
{
    use RouteHandlerResolverTrait;

    /**
     * @var CallableResolver
     */
    private $resolver;

    public function __construct(CallableResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @inheritDoc
     */
    public function resolve($callable): Closure
    {
        if (is_string($callable) && 0 !== strpos($callable, '@')) {
            $callable = explode('@', $callable, 2);
        }

        try {
            $handler = Closure::fromCallable($this->resolver->resolve($callable));
        } catch (NotCallableException | ReflectionException $e) {
            throw new WrongRouteHandlerException($e->getMessage(), $callable, $e);
        }

        $this->validateReturnType($handler, $callable);

        return $handler;
    }
}
