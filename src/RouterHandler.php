<?php

namespace Horizom\Routing;

use FastRoute\Dispatcher;
use Horizom\Routing\Exceptions\MethodNotAllowedException;
use Horizom\Routing\Exceptions\NotFoundException;
use Horizom\Routing\Interfaces\RouteInterface;
use Horizom\Routing\Interfaces\RouterHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterHandler implements RouterHandlerInterface
{
    public const ROUTE_ARGS = 'args';

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                [, $route, $routeArgs] = $routeInfo;

                $request = $request
                    ->withAttribute(RouteInterface::class, $route)
                    ->withAttribute(self::ROUTE_ARGS, $routeArgs);

                return $route->getPipe()->handle($request);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException($routeInfo[1]);
            case Dispatcher::NOT_FOUND:
                throw new NotFoundException();
            default:
                throw new NotFoundException();
        }
    }
}
