<?php

namespace App\Middleware;

use App\ValueObject\VehiclesQuery;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

class VehiclesRequestGetMiddleware implements ServerMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        Assert::string($request->getAttribute('manufacturer'));
        Assert::string($request->getAttribute('model'));
        Assert::integerish($request->getAttribute('year'));

        return $delegate->process(
            $request->withAttribute(
                'query',
                new VehiclesQuery(
                    $request->getAttribute('manufacturer'),
                    $request->getAttribute('model'),
                    (int) $request->getAttribute('year')
                )
            )
        );
    }
}
