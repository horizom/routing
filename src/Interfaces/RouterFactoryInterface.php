<?php

namespace Horizom\Routing\Interfaces;

use Psr\Container\ContainerInterface;

interface RouterFactoryInterface
{
    public function create(ContainerInterface $container): RouterInterface;
}
