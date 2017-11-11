<?php

namespace App\Factory;

use App\Middleware\CacheMiddleware;
use Interop\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class CacheMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CacheMiddleware(
            $container->get(CacheInterface::class)
        );
    }
}
