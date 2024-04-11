<?php

namespace Horizom\Routing;

use FastRoute\Dispatcher\GroupCountBased;
use Horizom\Routing\Interfaces\RouterInterface;
use Horizom\Routing\Interfaces\RouterHandlerFactoryInterface;

class RouterHandlerFactory implements RouterHandlerFactoryInterface
{
    public function create(RouterInterface $router): RouterHandler
    {
        return new RouterHandler(
            new GroupCountBased($router->getData())
        );
    }
}
