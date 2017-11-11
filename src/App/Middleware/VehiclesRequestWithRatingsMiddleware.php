<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;

class VehiclesRequestWithRatingsMiddleware implements ServerMiddlewareInterface
{
    const PARAM = 'withRating';

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return $delegate->process(
            $request->withAttribute(
                self::PARAM,
                $this->getWithRatingValue($request->getQueryParams())
            )
        );
    }

    private function getWithRatingValue(array $queryParams) : bool
    {
        if (isset($queryParams[self::PARAM])) {
            $value = $queryParams[self::PARAM];

            if ($value === 'true') {
                return true;
            }
        }

        return false;
    }
}
