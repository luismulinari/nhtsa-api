<?php

namespace App\Factory;

use App\HttpClient\ClientInterface;
use App\Service\VehiclesProvider;
use Interop\Container\ContainerInterface;

class VehiclesProviderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new VehiclesProvider(
            $container->get(ClientInterface::class)
        );
    }
}
