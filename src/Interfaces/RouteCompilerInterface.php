<?php

namespace Horizom\Routing\Interfaces;

interface RouteCompilerInterface
{
    public function compile(RouteInterface $route): void;
}
