<?php

namespace App\Factory;

use App\Service\VehiclesProvider;
use App\Service\VehiclesProviderStrategy;
use App\Service\VehiclesRatingsProvider;
use Interop\Container\ContainerInterface;

class VehiclesProviderStrategyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new VehiclesProviderStrategy(
            $container->get(VehiclesProvider::class),
            $container->get(VehiclesRatingsProvider::class)
        );
    }
}