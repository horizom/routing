<?php

namespace Horizom\Routing;

use Invoker\CallableResolver;
use Horizom\Routing\HandlerResolvers\LazyRouteHandlerResolver;
use Horizom\Routing\HandlerResolvers\PhpDiRouteHandlerResolver;
use Psr\Container\ContainerInterface;

class RouterLazyFactory extends RouterFactory
{
    public function create(ContainerInterface $container): Router
    {
        return new Router(
            new \FastRoute\RouteParser\Std(),
            new \FastRoute\DataGenerator\GroupCountBased(),
            new LazyRouteHandlerResolver(
                new PhpDiRouteHandlerResolver(
                    new CallableResolver($container)
                )
            ),
            $this->getCompiler($container),
            new RouterHandlerFactory()
        );
    }
}
