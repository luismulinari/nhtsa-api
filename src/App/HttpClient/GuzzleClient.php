<?php

namespace App\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient extends Client implements ClientInterface
{
    public function __construct(array $config = [])
    {
        $config['base_uri'] = ClientInterface::BASE_URI;
        //TODO: add timeout
        parent::__construct($config);
    }

    public function get(string $uri, array $options = []) : ResponseInterface {
        return parent::get($uri, $options);
    }

    public function getAsync(string $uri, array $options = []) : PromiseInterface {
        return parent::getAsync($uri, $options);
    }
}
