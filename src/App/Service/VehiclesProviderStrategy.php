<?php

namespace App\Service;

class VehiclesProviderStrategy
{
    /**
     * @var VehiclesProvider
     */
    private $vehiclesProvider;

    /**
     * @var VehiclesRatingsProvider
     */
    private $vehiclesRatingsProvider;

    /**
     * @param VehiclesProvider $vehiclesProvider
     * @param VehiclesRatingsProvider $vehiclesRatingsProvider
     */
    public function __construct(
        VehiclesProvider $vehiclesProvider,
        VehiclesRatingsProvider $vehiclesRatingsProvider
    ) {
        $this->vehiclesProvider = $vehiclesProvider;
        $this->vehiclesRatingsProvider = $vehiclesRatingsProvider;
    }

    /**
     * @param bool $withRatings
     * @return VehiclesProviderInterface
     */
    public function getProvider(bool $withRatings) : VehiclesProviderInterface
    {
        if ($withRatings) {
            return $this->vehiclesRatingsProvider;
        }

        return $this->vehiclesProvider;
    }
}
