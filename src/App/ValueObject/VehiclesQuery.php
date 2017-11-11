<?php

namespace App\ValueObject;

class VehiclesQuery
{
    /**
     * @var string
     */
    private $manufacturer;

    /**
     * @var string
     */
    private $model;

    /**
     * @var int
     */
    private $year;

    /**
     * @param string $manufacturer
     * @param string $model
     * @param int $year
     */
    public function __construct(string $manufacturer, string $model, int $year)
    {
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getManufacturer() : string
    {
        return $this->manufacturer;
    }

    /**
     * @return string
     */
    public function getModel() : string
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function getYear() : int
    {
        return $this->year;
    }
}
