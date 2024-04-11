<?php

namespace Horizom\Routing\Interfaces;

use Closure;

interface RouteHandlerResolverInterface
{
    /**
     * @param string|string[]|callable|Closure $callable
     *
     * @return Closure
     */
    public function resolve($callable): Closure;
}
