<?php

namespace App\Factory;

use App\HttpClient\ClientInterface;
use App\Service\VehiclesProvider;
use App\Service\VehiclesRatingsProvider;
use Interop\Container\ContainerInterface;

class VehiclesRatingsProviderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new VehiclesRatingsProvider(
            $container->get(ClientInterface::class),
            $container->get(VehiclesProvider::class)
        );
    }
}
