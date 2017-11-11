<?php

namespace App\Service;

use App\Model\Vehicles;
use App\ValueObject\VehiclesQuery;

interface VehiclesProviderInterface
{
    /**
     * @param VehiclesQuery $vehiclesQuery
     * @return Vehicles
     */
    public function getAll(VehiclesQuery $vehiclesQuery) : Vehicles;
}
