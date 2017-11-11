<?php

namespace App;

use App\Action\VehiclesAction;
use App\Factory\GuzzleClientFactory;
use App\Middleware\CacheMiddleware;
use App\Factory\CacheMiddlewareFactory;
use App\Factory\VehiclesActionFactory;
use App\HttpClient\ClientInterface;
use App\Middleware\VehiclesRequestGetMiddleware;
use App\Middleware\VehiclesRequestPostMiddleware;
use App\Middleware\VehiclesRequestWithRatingsMiddleware;
use App\Service\VehiclesProvider;
use App\Factory\VehiclesProviderFactory;
use App\Service\VehiclesProviderStrategy;
use App\Factory\VehiclesProviderStrategyFactory;
use App\Service\VehiclesRatingsProvider;
use App\Factory\VehiclesRatingsProviderFactory;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\NullCache;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'routes' => $this->getRoutes()
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'invokables' => [
                CacheInterface::class => NullCache::class //TODO: use a real cache
            ],
            'factories'  => [
                Action\VehiclesAction::class => VehiclesActionFactory::class,
                VehiclesProvider::class => VehiclesProviderFactory::class,
                VehiclesRatingsProvider::class => VehiclesRatingsProviderFactory::class,
                VehiclesProviderStrategy::class => VehiclesProviderStrategyFactory::class,
                ClientInterface::class => GuzzleClientFactory::class,
                CacheMiddleware::class => CacheMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * Returns the app routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return [
            [
                'name'            => 'vehicles.list.get',
                'path'            => '/vehicles2/{year:\d+}/{manufacturer}/{model}',
                'middleware'      => [
                    VehiclesRequestWithRatingsMiddleware::class,
                    VehiclesRequestGetMiddleware::class,
                    CacheMiddleware::class,
                    VehiclesAction::class,
                ],
                'allowed_methods' => ['GET'],
            ],
            [
                'name'            => 'vehicles.list.post',
                'path'            => '/vehicles',
                'middleware'      => [
                    VehiclesRequestWithRatingsMiddleware::class,
                    VehiclesRequestPostMiddleware::class,
                    VehiclesAction::class,
                ],
                'allowed_methods' => ['POST'],
            ],
        ];
    }
}
