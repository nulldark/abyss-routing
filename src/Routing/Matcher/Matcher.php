<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of nulldark/routing
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Nulldark\Routing\Matcher;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Nulldark\Routing\RouteMatch;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Dominik Szamburski
 * @package Routing
 * @subpackage Matcher
 * @license LGPL-2.1
 * @version 0.1.0
 */
class Matcher implements MatcherInterface
{
    /** @var string[] $allow */
    private array $allow;

    /** @var ServerRequestInterface $request */
    private ServerRequestInterface $request;

    public function __construct(
        protected RouteCollection $routes
    ) {
    }

    /**
     * @inheritDoc
     */
    public function matchRequest(ServerRequestInterface $request): RouteMatch
    {
        $this->request = $request;

        return $this->match($request->getUri()->getPath());
    }

    /**
     * Match's given path to find a route.
     *
     * @param string $pathinfo
     * @return RouteMatch
     *
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    protected function match(string $pathinfo): RouteMatch
    {
        $this->allow = [];

        $pathinfo = '/' . \ltrim(\trim($pathinfo), '/');

        if ($route = $this->matchRoute(\rawurldecode($pathinfo) ?: '/', $this->routes)) {
            return $route;
        }

        if (\count($this->allow) > 0) {
            throw new MethodNotAllowedException(
                \array_unique($this->allow)
            );
        }

        throw new RouteNotFoundException(sprintf("No route found for '%s'", $pathinfo), 404);
    }

    /**
     * Match's given path to set of routes.
     *
     * @param string $pathinfo
     * @param RouteCollection $routes
     * @return RouteMatch|null
     */
    protected function matchRoute(string $pathinfo, RouteCollection $routes): ?RouteMatch
    {
        if ('HEAD' === $method = $this->request->getMethod()) {
            $method = 'GET';
        }

        foreach ($routes as $name => $route) {
            $compiledRoute = $route->compile();
            $requiredMethods = $route->getMethods();

            if ($requiredMethods && !\in_array($method, $requiredMethods)) {
                $this->allow = \array_merge($this->allow, $requiredMethods);
                continue;
            }

            $regex = $compiledRoute->getRegex();

            $pos = strrpos($regex, '$');
            $hasTrailingSlash = '/' === $regex[$pos - 1];
            $regex = substr_replace($regex, '/?$', $pos - $hasTrailingSlash, 1 + $hasTrailingSlash);

            if (!preg_match($regex, $pathinfo, $matches)) {
                continue;
            }

            foreach ($matches as $key => $match) {
                if (is_numeric($key) || $match === null) {
                    continue;
                }

                $route->setArg($key, $match);
            }

            return new RouteMatch($route->getDefaults(), $matches);
        }
        return null;
    }
}
