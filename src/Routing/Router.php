<?php

namespace Nulldark\Routing;

use Nulldark\Routing\Matcher\Matcher;
use Nulldark\Routing\Matcher\MatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    protected ?MatcherInterface $matcher = null;

    public function __construct(
        private RouteCollection $routes
    ) {

    }

    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): Route
    {
        return $this->getMatcher()->matchRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * Sets the set of routes
     *
     * @param RouteCollection $routes
     * @return $this
     */
    public function setRouteCollection(RouteCollection $routes): self
    {
        $this->routes = $routes;
        return $this;
    }


    /**
     * Gets a matcher
     *
     * @return MatcherInterface
     */
    public function getMatcher(): MatcherInterface {
        if (null !== $this->matcher) {
            return $this->matcher;
        }

        return new Matcher(
            $this->routes
        );
    }
}
