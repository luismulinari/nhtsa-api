<?php

namespace App\Service;

use App\Model\Vehicle;
use App\Model\Vehicles;
use App\Model\VehicleWithRating;
use App\ValueObject\VehiclesQuery;
use App\HttpClient\ClientInterface;
use GuzzleHttp\Promise;

class VehiclesRatingsProvider implements VehiclesProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var VehiclesProvider
     */
    private $vehiclesProvider;

    /**
     * @param ClientInterface $client
     * @param VehiclesProvider $vehiclesProvider
     */
    public function __construct(ClientInterface $client, VehiclesProvider $vehiclesProvider)
    {
        $this->client = $client;
        $this->vehiclesProvider = $vehiclesProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(VehiclesQuery $vehiclesQuery) : Vehicles
    {
        $vehiclesList = $this->vehiclesProvider->getAll($vehiclesQuery)->getVehicles();

        $vehiclesListWithRatings = new Vehicles([]);

        $promises = [];

        foreach ($vehiclesList as $key => $vehicle) {
            $apiUrl = $this->getRatingUrl($vehicle);

            $promises[$key] = $this->client->getAsync($apiUrl);
        }

        $results = Promise\unwrap($promises);

        foreach ($results as $key => $response) {
            $result = \GuzzleHttp\json_decode($response->getBody(), true);

            if (isset($result['Results'][0]['OverallRating'])) {
                $vehiclesListWithRatings->addVehicle(
                    new VehicleWithRating(
                        $vehiclesList[$key]->getId(),
                        $vehiclesList[$key]->getDescription(),
                        $result['Results'][0]['OverallRating']
                    )
                );
            }
        }


        return $vehiclesListWithRatings;
    }

    /**
     * @param Vehicle $vehicle
     * @return string
     */
    private function getRatingUrl(Vehicle $vehicle)
    {
        return sprintf(
            'webapi/api/SafetyRatings/VehicleId/%d?format=json',
            $vehicle->getId()
        );
    }
}
