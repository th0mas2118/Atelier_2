<?php

namespace reunionou\comment\actions;

use Psr\Container\ContainerInterface;


abstract class AbstractAction
{
    protected ContainerInterface $container;
    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }
}