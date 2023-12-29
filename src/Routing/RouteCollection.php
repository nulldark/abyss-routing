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

use Abyss\Routing\Matcher\MatcherInterface;
use Abyss\Routing\Matcher\MethodMatcher;
use Abyss\Routing\Matcher\PathMatcher;
use Abyss\Routing\Exception\MethodNotAllowedException;
use Abyss\Routing\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Traversable;

/**
 * @package Abyss\Routing
 * @since 0.1.0
 */
class RouteCollection implements RouteCollectionInterface
{
    /** @var string[] $allow */
    private array $allow;

    /**
     * The collection of routes.
     *
     * @var array<string, Route> $routes
     */
    private array $routes = [];

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return \count($this->routes);
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(string $method = null): array
    {
        if ($method === null) {
            return $this->routes;
        }

        $routes = \array_map(function (Route $route) use ($method) {
            if (\in_array($method, $route->methods())) {
                return $route;
            }

            return null;
        }, $this->routes);

        return \array_filter($routes);
    }

    /**
     * @inheritDoc
     */
    public function add(Route $route): Route
    {
        $this->routes[\spl_object_hash($route)] = $route;

        return $route;
    }


    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): Route
    {
        $this->allow = [];

        if ($route = $this->matchCollection($request)) {
            return $route;
        }

        if (\count($this->allow) > 0) {
            throw new MethodNotAllowedException(sprintf(
                'The %s method is not supported for route %s. Supported methods: %s.',
                $request->getMethod(),
                $request->getUri()->getPath(),
                \implode(', ', \array_unique($this->allow))
            ));
        }

        throw new RouteNotFoundException(sprintf(
            'The route %s could not be found.',
            $request->getUri()->getPath()
        ));
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Route|null
     */
    protected function matchCollection(ServerRequestInterface $request): ?Route
    {
        foreach ($this->routes as $route) {
            if ($this->matchRoute($request, $route) === true) {
                return $route;
            }
        }

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Route $route
     *
     * @return bool
     */
    protected function matchRoute(ServerRequestInterface $request, Route $route): bool
    {
        foreach ($this->getMatchers() as $matcher) {
            if ($matcher->match($route, $request) === false) {
                if ($matcher instanceof MethodMatcher) {
                    $this->allow[] = $request->getMethod();
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Gets a matchers.
     *
     * @return \Generator<MatcherInterface>
     */
    private function getMatchers(): \Generator
    {
        yield new MethodMatcher();
        yield new PathMatcher();
    }
}
