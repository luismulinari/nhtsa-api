<?php

namespace AppTest\Service;

use App\HttpClient\ClientInterface;
use App\Model\Vehicle;
use App\Model\Vehicles;
use App\Service\VehiclesProvider;
use App\ValueObject\VehiclesQuery;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;

class VehiclesProviderTest extends TestCase
{
    /**
     * @var ClientInterface|ObjectProphecy
     */
    private $client;

    /**
     * @var VehiclesProvider
     */
    protected $service;

    public function setUp()
    {
        $this->client = $this->prophesize(ClientInterface::class);

        $this->service = new VehiclesProvider(
            $this->client->reveal()
        );
    }

    public function testGetAllShouldReturnAListOfVehicles()
    {
        $query = new VehiclesQuery('Audi', 'A3', 2015);

        $url = 'webapi/api/SafetyRatings/modelyear/2015/make/Audi/model/A3?format=json';

        $json = <<<JSON
{
  "Count": 4,
  "Message": "Results returned successfully",
  "Results": [
    {
      "VehicleDescription": "2015 Audi A3 4 DR AWD",
      "VehicleId": 9403
    },
    {
      "VehicleDescription": "2015 Audi A3 4 DR FWD",
      "VehicleId": 9408
    }
  ]
}
JSON;

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($json);

        $this->client->get($url)->willReturn($response->reveal());

        $vehicle1 = new Vehicle(9403, '2015 Audi A3 4 DR AWD');
        $vehicle2 = new Vehicle(9408, '2015 Audi A3 4 DR FWD');

        $expectedResult = new Vehicles([$vehicle1, $vehicle2]);

        $this->assertEquals($expectedResult, $this->service->getAll($query));
    }

}

