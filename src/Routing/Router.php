<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of abyss/routing
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

namespace Abyss\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Abyss\Routing
 * @version 2.0.0
 */
class Router implements RouterInterface
{
    /**
     * The collection of routes.
     *
     * @var RouteCollectionInterface $routes
     */
    private RouteCollectionInterface $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): Route
    {
        $route =  $this->routes->match($request);

        preg_match($route->compiled()->getRegex(), $request->getUri()->getPath(), $matches);

        foreach ($matches as $key => $value) {
            if (is_numeric($key) || $value === null) {
                continue;
            }

            $route->setParameter($key, $value);
        }

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection(): RouteCollectionInterface
    {
        return $this->routes;
    }

    /**
     * @inheritDoc
     */
    public function setRouteCollection(RouteCollectionInterface $routeCollection): self
    {
        $this->routes = $routeCollection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['GET'], $path, $callback);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['POST'], $path, $callback);
    }

    /**
    /**
     * @inheritDoc
     */
    public function put(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['PUT'], $path, $callback);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['PATCH'], $path, $callback);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['DELETE'], $path, $callback);
    }

    /**
     * @inheritDoc
     */
    public function options(string $path, \Closure|string $callback): Route
    {
        return $this->addRoute(['OPTIONS'], $path, $callback);
    }

    /**
     * Adds new route to the collection.
     *
     * @param string[] $methods
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function addRoute(array $methods, string $path, \Closure|string $callback): Route
    {
        return $this->routes->add(
            new Route($methods, $path, $callback)
        );
    }
}
