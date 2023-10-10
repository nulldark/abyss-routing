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

use Nulldark\Routing\CompiledRoute;
use Nulldark\Routing\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CompiledRoute::class)]
class CompiledRouteTest extends TestCase
{
    /**
     * @covers \Nulldark\Routing\CompiledRoute::getRegex
     * @covers \Nulldark\Routing\CompiledRoute::getVariables
     * @covers \Nulldark\Routing\CompiledRoute::getTokens
     *
     * @return void
     */
    public function testGetters()
    {
        $compiled = new CompiledRoute('regex', ['variables'], ['tokens']);

        $this->assertEquals('regex', $compiled->getRegex());
        $this->assertEquals(['variables'], $compiled->getVariables());
        $this->assertEquals(['tokens'], $compiled->getTokens());
    }
}
