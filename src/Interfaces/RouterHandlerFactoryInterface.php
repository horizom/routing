<?php

namespace Horizom\Routing\Interfaces;

interface RouterHandlerFactoryInterface
{
    public function create(RouterInterface $collector): RouterHandlerInterface;
}
