<?php

namespace App\Factory;

use App\HttpClient\GuzzleClient;
use Interop\Container\ContainerInterface;

class GuzzleClientFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new GuzzleClient();
    }
}
