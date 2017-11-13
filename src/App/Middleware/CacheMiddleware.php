<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use Zend\Diactoros\Response;

class CacheMiddleware implements ServerMiddlewareInterface
{
    const PREFIX = 'cache.nhtsa';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * CacheMiddleware constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface|Response
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $arraySerializer = new Response\ArraySerializer();

        $cacheKey = $this->getKey($request);

        $result = $this->cache->get($cacheKey);
        if ($result !== null) {
            return $arraySerializer->fromArray($result);
        }

        $response = $delegate->process($request);

        if ($this->canSave($request, $response)) {
            $this->cache->set($cacheKey, $arraySerializer->toArray($response));
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    private function canSave(RequestInterface $request, ResponseInterface $response) : bool
    {
        if ($request->getMethod() !== 'GET') {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if ($response->hasHeader('location')) {
            return false;
        }

        $cacheControl = $response->getHeaderLine('Cache-Control');
        if ($cacheControl
            && (stripos($cacheControl, 'no-cache') !== false || stripos($cacheControl, 'no-store') !== false)) {
            return false;
        }

        return true;
    }

    private function getKey(ServerRequestInterface $request) : string
    {
        return sprintf(
            '%s.%s',
            self::PREFIX,
            base64_encode(
                sprintf(
                    '%s?%s',
                    $request->getUri()->getPath(),
                    $request->getUri()->getQuery()
                )
            )
        );
    }
}
