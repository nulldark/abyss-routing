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

use Traversable;

/**
 * @implements \IteratorAggregate<string, Route>
 */
final class RouteCollection implements \IteratorAggregate, \Countable
{
    /** @var array<string, Route> $routes */
    private array $routes = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->routes);
    }

    /**
     * @param string $name
     * @param Route $route
     * @return void
     */
    public function add(string $name, Route $route): void
    {
        $this->routes[$name] = $route;
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }


    /**
     * @param string $name
     * @return Route|null
     */
    public function get(string $name): ?Route
    {
        return $this->routes[$name] ?? null;
    }

    /**
     * @param self $collection
     * @return void
     */
    public function mergeCollection(self $collection): void
    {
        foreach ($collection->all() as $name => $route) {
            unset($this->routes[$name]);
            $this->routes[$name] = $route;
        }
    }
}
