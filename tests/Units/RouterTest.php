<?php

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