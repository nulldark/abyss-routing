<?php

namespace Nulldark\Routing;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * Match a given request with a set of routes.
     *
     * @param ServerRequestInterface $request
     * @return Route
     *
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request): Route;

    /**
     * Get set of routes
     *
     * @return RouteCollection
     */
    public function getRouteCollection(): RouteCollection;
}
