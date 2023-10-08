<?php

namespace Nulldark\Tests\Units;

use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteCollection::class)]
class RouteCollectionTest extends TestCase
{
    /**
     * @covers \Nulldark\Routing\RouteCollection::count
     * @return void
     */
    public function testCount(): void
    {
        $collection = new RouteCollection();
        $collection->add('route_1', new Route('/'));
        $collection->add('route_2', new Route('/'));

        $this->assertCount(2, $collection);
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::add
     * @return void
     */
    public function testRoute(): void
    {
        $collection = new RouteCollection();
        $collection->add('foo', $route = new Route('/bar'));

        $this->assertNull($collection->get('bar'));
        $this->assertEquals($route, $collection->get('foo'));
        $this->assertEquals(['foo' => $route], $collection->all());
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::add
     * @return void
     */
    public function testOverriddenRoute(): void
    {
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo'));
        $collection->add('foo', new Route('/bar'));

        $this->assertEquals('/bar', $collection->get('foo')->getPath());
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::getIterator
     * @return void
     */
    public function testIterator(): void
    {
        $collection = new RouteCollection();

        $collection->add('foo', $foo = new Route('/foo'));
        $collection->add('bar', $bar = new Route('/bar'));

        $this->assertInstanceOf(\ArrayIterator::class, $collection->getIterator());
        $this->assertSame([
            'foo' => $foo,
            'bar' => $bar
        ], $collection->getIterator()->getArrayCopy());
    }
}
