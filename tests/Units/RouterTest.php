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

namespace Nulldark\Tests\Units;

use Nulldark\Routing\Matcher\MatcherInterface;
use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Nulldark\Routing\Router;
use Nulldark\Tests\Mock\ServerRequestMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Router::class)]
class RouterTest extends TestCase
{

    /**
     * @covers \Nulldark\Routing\Router::getMatcher
     * @return void
     */
    public function testGetMatcher(): void
    {
        $router = new Router(
            new RouteCollection()
        );

        $matcher = $router->getMatcher();

        $this->assertInstanceOf(MatcherInterface::class, $matcher);
    }

    /**
     * @covers \Nulldark\Routing\Router::match
     * @return void
     */
    public function testMatch(): void
    {
        $route = new Route('/');

        $routes = new RouteCollection();
        $routes->add('foo', $route);

        $router = new Router($routes);
        $result = $router->match(ServerRequestMock::create());

        $this->assertEquals($result, $route);
    }

    public function testRouteCollectionsMethods(): void
    {
        $routes = new RouteCollection();
        $routes->add('foo_1', new Route('/'));

        $router = new Router($routes);
        $this->assertEquals($routes, $router->getRouteCollection());

        $routes_2 = new RouteCollection();
        $routes_2->add('foo_2', new Route('/foo'));

        $router->setRouteCollection($routes_2);
        $this->assertEquals($routes_2, $router->getRouteCollection());
    }
}