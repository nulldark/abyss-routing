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

namespace Nulldark\Routing;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Nulldark\Routing
 * @version 2.0.0
 */
interface RouterInterface
{
    /**
     * Matches a given request with a set of routes.
     *
     * @param ServerRequestInterface $request
     *
     * @return Route
     *
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request): Route;

    /**
     * Sets a new routes collection.
     *
     * @param RouteCollectionInterface $routeCollection
     *
     * @return self
     */
    public function setRouteCollection(RouteCollectionInterface $routeCollection): self;

    /**
     * Gets a route collection.
     *
     * @return RouteCollectionInterface
     */
    public function getRouteCollection(): RouteCollectionInterface;

    /**
     *  Adds GET route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function get(string $path, \Closure|string $callback): Route;
    /**
     * Adds POST route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function post(string $path, \Closure|string $callback): Route;

    /**
     * Adds PUT route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function put(string $path, \Closure|string $callback): Route;

    /**
     * Adds PATCH route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function patch(string $path, \Closure|string $callback): Route;

    /**
     * Adds DELETE route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function delete(string $path, \Closure|string $callback): Route;

    /**
     * Adds OPTIONS route.
     *
     * @param string $path
     * @param \Closure|string $callback
     *
     * @return Route
     */
    public function options(string $path, \Closure|string $callback): Route;
}
