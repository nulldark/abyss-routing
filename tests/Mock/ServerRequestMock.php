<?php

namespace Nulldark\Tests\Mock;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestMock
{
    /**
     * @param string $uri
     * @param string $method
     * @return ServerRequestInterface
     */
    public static function create(string $uri = '/', string $method = 'GET'): ServerRequestInterface
    {
        return new ServerRequest(
            [],
            [],
            $uri,
            $method
        );
    }
}
