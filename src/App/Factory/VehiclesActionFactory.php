<?php

namespace App\Factory;

use App\Action\VehiclesAction;
use App\Service\VehiclesProviderStrategy;
use Interop\Container\ContainerInterface;

class VehiclesActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new VehiclesAction(
            $container->get(VehiclesProviderStrategy::class)
        );
    }
}
