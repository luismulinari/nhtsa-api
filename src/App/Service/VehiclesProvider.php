<?php

namespace App\Service;

use App\Model\Vehicle;
use App\Model\Vehicles;
use App\ValueObject\VehiclesQuery;
use App\HttpClient\ClientInterface;

class VehiclesProvider implements VehiclesProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(VehiclesQuery $vehiclesQuery) : Vehicles
    {
        $apiUrl = $this->getUrl($vehiclesQuery);

        $result = \GuzzleHttp\json_decode($this->client->get($apiUrl)->getBody(), true);

        return $this->decodeJson($result);
    }

    /**
     * @param array $result
     * @return Vehicles
     */
    private function decodeJson(array $result)
    {
        $vehicles = array_map(function ($vehicle) {
            return new Vehicle(
                (int) $vehicle['VehicleId'],
                $vehicle['VehicleDescription']
            );
        }, $result['Results']);

        return new Vehicles($vehicles);
    }

    /**
     * @param VehiclesQuery $vehiclesQuery
     * @return string
     */
    private function getUrl(VehiclesQuery $vehiclesQuery)
    {
        return sprintf(
            'webapi/api/SafetyRatings/modelyear/%d/make/%s/model/%s?format=json',
            $vehiclesQuery->getYear(),
            $vehiclesQuery->getManufacturer(),
            $vehiclesQuery->getModel()
        );
    }
}
