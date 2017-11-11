<?php

namespace App\Action;

use App\Middleware\VehiclesRequestWithRatingsMiddleware;
use App\Service\VehiclesProviderStrategy;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

class VehiclesAction implements ServerMiddlewareInterface
{
    /**
     * @var VehiclesProviderStrategy
     */
    private $vehiclesProviderStrategy;

    public function __construct(VehiclesProviderStrategy $vehiclesProviderStrategy)
    {
        $this->vehiclesProviderStrategy = $vehiclesProviderStrategy;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return JsonResponse
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $provider = $this->vehiclesProviderStrategy->getProvider(
            $request->getAttribute(VehiclesRequestWithRatingsMiddleware::PARAM)
        );

        return new JsonResponse(
            $provider->getAll($request->getAttribute('query'))
        );
    }
}
