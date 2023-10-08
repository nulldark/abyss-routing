<?php

namespace Nulldark\Tests\Units;

use Nulldark\Routing\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Route::class)]
class RouteTest extends TestCase
{
    /**
     * @covers \Nulldark\Routing\Route::__construct
     * @return void
     */
    public function testConstructor(): void
    {
        $route = new Route('/');
        $this->assertEquals('/', $route->getPath());
    }

    /**
     * @covers \Nulldark\Routing\Route::setPath
     * @covers \Nulldark\Routing\Route::getPath
     * @return void
     */
    public function testPath(): void
    {
        $route = new Route('/foo');
        $this->assertEquals('/foo', $route->getPath());

        $route->setPath('');
        $this->assertEquals('/', $route->getPath());

        $route->setPath('//bar');
        $this->assertEquals('/bar', $route->getPath());
    }
}
