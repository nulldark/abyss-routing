<?php

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
