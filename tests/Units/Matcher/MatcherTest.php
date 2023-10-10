<?php

namespace Nulldark\Tests\Units\Matcher;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Nulldark\Routing\Matcher\Matcher;
use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Nulldark\Tests\Mock\ServerRequestMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

#[CoversClass(Matcher::class)]
class MatcherTest extends TestCase
{
    private RouteCollection $routes;

    public function setUp(): void
    {
        $this->routes = new RouteCollection();
    }

    /**
     * @covers \Nulldark\Routing\Matcher\Matcher::match
     * @return void
     */
    public function testMatch(): void
    {
        $this->routes->add('bar', new Route('/path'));
        $this->routes->add('foo', $route = new Route('/'));

        $matcher = new Matcher($this->routes);
        $ret = $matcher->matchRequest(
            ServerRequestMock::create()
        );

        $this->assertEquals($route, $ret);
    }

    /**
     * @covers \Nulldark\Routing\Matcher\Matcher::match
     * @return void
     */
    public function testMatchNotFoundThrowsException(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->expectExceptionMessage("No route found for '/'");

        $matcher = new Matcher($this->routes);
        $matcher->matchRequest(
            ServerRequestMock::create()
        );
    }

    /**
     * @covers \Nulldark\Routing\Matcher\Matcher::match
     * @return void
     */
    public function testMatchNotAllowedMethod(): void
    {
        $this->expectException(MethodNotAllowedException::class);

        $this->routes->add('foo', new Route('/', ['POST']));

        $matcher = new Matcher($this->routes);
        $matcher->matchRequest(
            ServerRequestMock::create()
        );
    }

    /**
     * @covers \Nulldark\Routing\Matcher\Matcher::match
     * @return void
     */
    public function testHEADMethodCovertToGET(): void
    {
        $this->routes->add('foo', $route = new Route('/'));

        $matcher = new Matcher($this->routes);

        $results = $matcher->matchRequest(
            ServerRequestMock::create('/', 'HEAD')
        );

        $this->assertEquals($route, $results);
    }

    /**
     * @covers \Nulldark\Routing\Matcher\Matcher::match
     * @return void
     */
    public function testPathWithVariable(): void
    {
        $this->routes->add('foo_1', $route_1 = new Route('/foo/{foo}'));
        $this->routes->add('foo_2', new Route('/bar/{bar}'));

        $matcher = new Matcher($this->routes);

        $results = $matcher->matchRequest(
            ServerRequestMock::create('/foo/1')
        );

        $this->assertInstanceOf(Route::class, $route_1);
        $this->assertEquals($results, $route_1);
        $this->assertEquals(1, $route_1->getArg('foo'));
    }
}
