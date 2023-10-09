<?php

namespace Nulldark\Tests\Units;

use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteCompiler::class)]
class RouteCompilerTest extends TestCase
{
    /**
     * @covers \Nulldark\Routing\RouteCompiler::compile
     * @covers \Nulldark\Routing\RouteCompiler::computeRegex
     * @dataProvider dataCompiledData
     *
     * @return void
     */
    public function testCompile(array $arguments, string $regex, array $variables, array $tokens): void
    {
        $r = new \ReflectionClass(Route::class);
        $route = $r->newInstanceArgs($arguments);

        $compiled = $route->compile();
        $this->assertEquals($regex, $compiled->getRegex());
        $this->assertEquals($variables, $compiled->getVariables());
        $this->assertEquals($tokens, $compiled->getTokens());
    }

    public static function dataCompiledData(): iterable
    {
        return [
            [
                ['/bar'], '#^\/bar$#sD', [], [['text', '/bar']]
            ],
            [
                ['/bar/{foo}'], '#^\/bar\/(?P<foo>[^\/]+)$#sD', ['foo'], [['text', '/bar/'], ['variable', '[^\/]+', 'foo']]
            ],
            [
                [''], '#^\/$#sD', [], [['text', '/']]
            ]
        ];
    }

    /**
     * @covers \Nulldark\Routing\RouteCompiler::compile
     * @return void
     */
    public function testRouteWithSameVariablesThrowsException(): void
    {
        $this->expectException(\LogicException::class);

        $route = new Route('/{foo}/{foo}');
        $route->compile();
    }

    /**
     * @covers \Nulldark\Routing\RouteCompiler::compile
     * @dataProvider dataVariableNamesStartingWithDigit
     * @param string $name
     * @return void
     */
    public function testRouteWithVariableStartedWithADigit(string $name): void
    {
        $this->expectException(\DomainException::class);
        $route = new Route('/{' . $name . '}');
        $route->compile();
    }

    public static function dataVariableNamesStartingWithDigit(): iterable
    {
        return [
            yield ['09'],
            yield ['123'],
            yield ['1e2'],
        ];
    }

    /**
     * @covers \Nulldark\Routing\RouteCompiler::compile
     * @return void
     */
    public function testRouteWithTooLongVariableName(): void
    {
        $this->expectException(\DomainException::class);
        $route = new Route(sprintf('/{%s}', str_repeat('b', RouteCompiler::VARIABLE_MAXIMUM_LENGTH + 1)));
        $route->compile();
    }
}
