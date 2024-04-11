<?php

namespace Horizom\Routing;

use Horizom\Dispatcher\MiddlewarePipeFactory;
use Horizom\Dispatcher\MiddlewareResolver;
use Horizom\Routing\HandlerResolvers\PhpDiRouteHandlerResolver;
use Horizom\Routing\Interfaces\RouterFactoryInterface;
use Horizom\Routing\Invokers\PhpDiRouteInvoker;
use Invoker\CallableResolver;
use Invoker\Invoker;
use Invoker\ParameterResolver;
use Psr\Container\ContainerInterface;

class RouterFactory implements RouterFactoryInterface
{
    /**
     * User defined request class aliases for DI
     *
     * @var array<class-string>
     */
    private $requestAliases;

    /**
     * @param array<class-string> $requestAliases
     */
    public function __construct(array $requestAliases = [])
    {
        $this->requestAliases = $requestAliases;
    }

    public function create(ContainerInterface $container): Router
    {
        return new Router(
            new \FastRoute\RouteParser\Std(),
            new \FastRoute\DataGenerator\GroupCountBased(),
            new PhpDiRouteHandlerResolver(
                new CallableResolver($container)
            ),
            $this->getCompiler($container),
            new RouterHandlerFactory()
        );
    }

    protected function getCompiler(ContainerInterface $container): RouteCompiler
    {
        return new RouteCompiler(
            new MiddlewarePipeFactory(
                new MiddlewareResolver($container)
            ),
            new PhpDiRouteInvoker(
                new Invoker(
                    new ParameterResolver\ResolverChain(
                        [
                            new ParameterResolver\TypeHintResolver(),
                            new ParameterResolver\AssociativeArrayResolver(),
                            new ParameterResolver\NumericArrayResolver(),
                            new ParameterResolver\Container\TypeHintContainerResolver($container),
                            new ParameterResolver\DefaultValueResolver(),
                        ]
                    ),
                    null, // performance optimization
                ),
                $this->requestAliases
            )
        );
    }
}
