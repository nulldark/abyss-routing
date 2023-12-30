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

namespace Abyss\Tests\Units\Matcher;

use Abyss\Routing\Matcher\PathMatcher;
use Abyss\Routing\Route;
use Abyss\Routing\Router;
use Abyss\Tests\Mock\ServerRequestMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PathMatcher::class)]
class PathMatcherTest extends TestCase
{
    public function testCheckGivenPathIsMatch(): void
    {
        $router = new Router();

        $route_1 = $router->get('/foo/{foo}', fn() => 'foo');
        $route_2 = $router->get('/bar/{bar}', fn() => 'bar');

        $matcher = new PathMatcher();

        self::assertTrue($matcher->match($route_1, ServerRequestMock::create('/foo/1')));
    }
}
