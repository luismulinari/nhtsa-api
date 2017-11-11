<?php

namespace App\Middleware;

use App\ValueObject\VehiclesQuery;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

class VehiclesRequestPostMiddleware implements ServerMiddlewareInterface
{
    const REQUIRED_PARAMS = ['manufacturer', 'model', 'modelYear'];

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = \GuzzleHttp\json_decode($request->getBody(), true);

        $this->checkRequiredParams($params);

        Assert::string($params['manufacturer'], '"manufacturer" should be "string"');
        Assert::string($params['model'], '"model" should be "string"');
        Assert::integerish($params['modelYear'], '"modelYear" should be "integer"');

        return $delegate->process(
            $request->withAttribute(
                'query',
                new VehiclesQuery(
                    $params['manufacturer'],
                    $params['model'],
                    (int) $params['modelYear']
                )
            )
        );
    }

    private function checkRequiredParams($params)
    {
        foreach (self::REQUIRED_PARAMS as $requiredParam) {
            if (!isset($params[$requiredParam])) {
                throw new \InvalidArgumentException(
                    sprintf(
                        '"%s" should be provided',
                        $requiredParam
                    )
                );
            }
        }
    }
}
