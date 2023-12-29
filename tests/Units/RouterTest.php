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

namespace Abyss\Tests\Units;

use Abyss\Routing\Route;
use Abyss\Routing\RouteCollectionInterface;
use Abyss\Routing\Router;
use Abyss\Tests\Mock\ServerRequestMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Router::class)]
class RouterTest extends TestCase
{
    public function testGetRouteCollection(): void
    {
        $router = new Router();

        $this->assertInstanceOf(
            RouteCollectionInterface::class,
            $router->getRouteCollection()
        );
    }

    public function testMatch(): void
    {
        $router = new Router();
        $router->get('/', fn () => 'test');

        $route = $router->match(ServerRequestMock::create());

        $this->assertInstanceOf(
            Route::class,
            $route
        );
    }
}
