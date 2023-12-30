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
use Abyss\Routing\RouteCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteCompiler::class)]
class RouteCompilerTest extends TestCase
{
    /**
     * @dataProvider dataCompiledData
     */
    public function testCompile(array $arguments, string $regex, array $variables, array $tokens): void
    {
        $r = new \ReflectionClass(Route::class);
        $route = $r->newInstanceArgs($arguments);

        $compiled = $route->compiled();

        self::assertEquals($regex, $compiled->getRegex());
        self::assertEquals($variables, $compiled->getVariables());
        self::assertEquals($tokens, $compiled->getTokens());
    }

    public static function dataCompiledData(): iterable
    {
        return [
            [
                [[], '/bar', fn() => 'test'],
                '#^\/bar$#sD', [],
                [['text', '/bar']],
            ],
            [
                [[], '/bar/{foo}', fn() => 'test'],
                '#^\/bar\/(?P<foo>[^\/]+)$#sD',
                ['foo'],
                [['text', '/bar/'], ['variable', '[^\/]+', 'foo']],
            ],
            [
                [[], '', fn() => 'test'],
                '#^\/$#sD',
                [],
                [['text', '/']],
            ],
        ];
    }

    /**
     * @covers \Abyss\Routing\RouteCompiler::compile
     * @return void
     */
    public function testRouteWithSameVariablesThrowsException(): void
    {
        $this->expectException(\LogicException::class);

        $route = new Route([], '/{foo}/{foo}', fn() => 'foo');
        $route->compiled();
    }

    /**
     * @dataProvider dataVariableNamesStartingWithDigit
     */
    public function testRouteWithVariableStartedWithADigit(string $name): void
    {
        $this->expectException(\DomainException::class);
        $route = new Route(['GET'], '/{' . $name . '}', fn() => 'foo');
        $route->compiled();
    }

    public static function dataVariableNamesStartingWithDigit(): iterable
    {
        return [
            yield ['09'],
            yield ['123'],
            yield ['1e2'],
        ];
    }

    public function testRouteWithTooLongVariableName(): void
    {
        $this->expectException(\DomainException::class);
        $route = new Route(
            [],
            sprintf('/{%s}', str_repeat('b', RouteCompiler::VARIABLE_MAXIMUM_LENGTH + 1)),
            fn () => 'foo',
        );
        $route->compiled();
    }
}
