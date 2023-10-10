<?php

namespace Nulldark\Routing\Matcher;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Nulldark\Routing\Route;
use Psr\Http\Message\ServerRequestInterface;

interface MatcherInterface
{
    /**
     * Match a given request with a set of routes.
     *
     * @param ServerRequestInterface $request
     * @return Route
     *
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function matchRequest(ServerRequestInterface $request): Route;
}
