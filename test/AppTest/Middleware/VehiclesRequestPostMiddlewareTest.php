<?php

namespace AppTest\Middleware;

use App\Middleware\VehiclesRequestPostMiddleware;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Diactoros\Request;
use Psr\Http\Message\ServerRequestInterface;

class VehiclesRequestPostMiddlewareTest extends TestCase
{
    /**
     * @var DelegateInterface|ObjectProphecy
     */
    private $delegate;

    /**
     * @var ServerRequestInterface|ObjectProphecy
     */
    private $request;

    /**
     * @var VehiclesRequestPostMiddleware
     */
    private $middleware;

    public function setUp()
    {
        $this->delegate = $this->prophesize(DelegateInterface::class);

        $this->request = $this->prophesize(ServerRequestInterface::class);

        $this->middleware = new VehiclesRequestPostMiddleware();
    }

    /**
     * @param string $invalidRequestBody
     * @param string $exceptionMessage
     *
     * @dataProvider invalidRequestsBody
     */
    public function testProcessShouldRaiseAnExceptionWhenARequiredParamIsMissing(string $invalidRequestBody, string $exceptionMessage)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->request->getBody()->willReturn($invalidRequestBody);

        $this->middleware->process(
            $this->request->reveal(),
            $this->delegate->reveal()
        );
    }

    /**
     * @param string $invalidRequestBody
     * @param string $exceptionMessage
     *
     * @dataProvider invalidTypes
     */
    public function testProcessShouldRaiseAnExceptionWhenAnyTypeIsInvalid(string $invalidType, string $exceptionMessage)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->request->getBody()->willReturn($invalidType);

        $this->middleware->process(
            $this->request->reveal(),
            $this->delegate->reveal()
        );
    }

    /**
     * @return array
     */
    public function invalidRequestsBody()
    {
        return [
            [
                '{"manufacturer": "Audi", "model": "A3"}',
                'modelYear" should be provided'
            ],
            [
                '{"model": "A3", "modelYear": 2015}',
                'manufacturer" should be provided'
            ],
            [
                '{"modelYear": "2015", "manufacturer": "Audi"}',
                'model" should be provided'
            ],
            [
                '{"modelYear": 2015}',
                'manufacturer" should be provided'
            ],
            [
                '{"manufacturer": "Audi"}',
                'model" should be provided'
            ],
            [
                '{"model": "A3"}',
                'manufacturer" should be provided'
            ],

            [
                '{}',
                'manufacturer" should be provided'
            ],
        ];
    }

    /**
     * @return array
     */
    public function invalidTypes()
    {
        return [
            [
                '{"manufacturer": "Audi", "model": "A3", "modelYear": "INVALID YEAR"}',
                '"modelYear" should be "integer"'
            ],
            [
                '{"manufacturer": "", "model": 3, "modelYear": "INVALID YEAR"}',
                '"model" should be "string"'
            ],
            [
                '{"manufacturer": 1, "model": "model", "modelYear": "INVALID YEAR"}',
                '"manufacturer" should be "string"'
            ],
        ];
    }
}
