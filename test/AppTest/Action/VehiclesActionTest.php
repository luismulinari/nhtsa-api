<?php

namespace AppTest\Action;

use App\Action\VehiclesAction;
use App\Middleware\VehiclesRequestWithRatingsMiddleware;
use App\Model\Vehicle;
use App\Model\Vehicles;
use App\Service\VehiclesProvider;
use App\Service\VehiclesProviderStrategy;
use App\ValueObject\VehiclesQuery;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;

class VehiclesActionTest extends TestCase
{
    /**
     * @var VehiclesAction
     */
    private $action;

    /**
     * @var ServerRequestInterface|ObjectProphecy
     */
    private $request;

    /**
     * @var VehiclesProviderStrategy|ObjectProphecy
     */
    private $strategy;

    /**
     * @var VehiclesProviderStrategy|ObjectProphecy
     */
    private $vehiclesProviderStrategy;

    public function setUp()
    {
        $this->vehiclesProviderStrategy = $this->prophesize(VehiclesProviderStrategy::class);

        $this->request = $this->prophesize(ServerRequestInterface::class);

        $this->strategy = $this->prophesize(VehiclesProvider::class);

        $this->vehiclesProviderStrategy->getProvider(false)->willReturn(
            $this->strategy->reveal()
        );

        $this->action = new VehiclesAction(
            $this->vehiclesProviderStrategy->reveal()
        );
    }

    public function testWithResponseWithSimpleRequest()
    {
        $query = new VehiclesQuery('Audi', 'A3', 2015);

        $this->request->getAttribute(VehiclesRequestWithRatingsMiddleware::PARAM)->willReturn(false);
        $this->request->getAttribute('query')->willReturn($query);

        $vehicle1 = new Vehicle('1', 'Vehicle 1');
        $vehicle2 = new Vehicle('2', 'Vehicle 2');
        $vehicles = new Vehicles([$vehicle1, $vehicle2]);

        $this->strategy->getAll($query)->willReturn($vehicles);

        $expectedResult = [
            'Count' => 2,
            'Results' => [
                [
                    'Description' => 'Vehicle 1',
                    'VehicleId' => 1,
                ],
                [
                    'Description' => 'Vehicle 2',
                    'VehicleId' => 2,
                ]
            ]
        ];

        $response = $this->action->process(
            $this->request->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedResult, true),
            (string) $response->getBody()
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['application/json'], $response->getHeader('content-type'));
    }
}
