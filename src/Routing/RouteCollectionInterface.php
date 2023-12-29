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

use Abyss\Routing\Exception\MethodNotAllowedException;
use Abyss\Routing\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Abyss\Routing
 * @since 0.1.0
 *
 * @extends \IteratorAggregate<array-key, Route>
 */
interface RouteCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * Matches a given request with a set of routes.
     *
     * @param ServerRequestInterface $request
     *
     * @return Route
     *
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function match(ServerRequestInterface $request): Route;

    /**
     * Returns all defined routes in the collection, if the `$method` parameter was
     * passed it only returns routes for the specified methode
     *
     * @param string|null $method
     *
     * @return array<string, Route>|Route[]
     */
    public function getRoutes(string $method = null): array;

    /**
     * Adds a new route to the collection.
     *
     * @param Route $route
     *
     * @return Route
     */
    public function add(Route $route): Route;
}
