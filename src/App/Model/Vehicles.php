<?php

namespace App\Model;

use Assert\Assertion;

class Vehicles implements \JsonSerializable
{
    /**
     * @var Vehicle[]|array
     */
    private $vehicles;

    /**
     * Vehicles constructor.
     * @param Vehicle[]|array $vehicles
     */
    public function __construct(array $vehicles)
    {
        Assertion::allIsInstanceOf($vehicles, Vehicle::class);

        $this->vehicles = $vehicles;
    }

    public function addVehicle(Vehicle $vehicle)
    {
        $this->vehicles[] = $vehicle;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->vehicles);
    }

    /**
     * @return Vehicle[]|array
     */
    public function getVehicles()
    {
        return $this->vehicles;
    }

    public function jsonSerialize()
    {
        return [
            'Count' => $this->count(),
            'Results' => $this->vehicles
        ];
    }
}
