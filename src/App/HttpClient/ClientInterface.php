<?php

namespace App\HttpClient;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    const BASE_URI = 'https://one.nhtsa.gov/';

    public function get(string $uri, array $options = []) : ResponseInterface;

    public function getAsync(string $uri, array $options = []) : PromiseInterface;
}
