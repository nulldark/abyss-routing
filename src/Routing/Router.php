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

use Nulldark\Routing\Matcher\Matcher;
use Nulldark\Routing\Matcher\MatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Dominik Szamburski
 * @package Routing
 * @license LGPL-2.1
 * @version 0.1.0
 */
class Router implements RouterInterface
{
    /** @var MatcherInterface|null $matcher */
    protected ?MatcherInterface $matcher = null;

    /** @var RouteCollection $routes */
    protected RouteCollection $routes;

    public function __construct(?RouteCollection $routes = null)
    {
        $this->routes = $routes ?: new RouteCollection();
    }

    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): RouteMatch
    {
        return $this->getMatcher()->matchRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * Sets the set of routes
     *
     * @param RouteCollection $routes
     * @return $this
     */
    public function setRouteCollection(RouteCollection $routes): self
    {
        $this->routes = $routes;
        return $this;
    }


    /**
     * Gets a matcher
     *
     * @return MatcherInterface
     */
    public function getMatcher(): MatcherInterface
    {
        if (null !== $this->matcher) {
            return $this->matcher;
        }

        return new Matcher(
            $this->routes
        );
    }
}
