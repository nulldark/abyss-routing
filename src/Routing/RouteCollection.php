<?php

namespace Nulldark\Routing;

use Traversable;

/**
 * @implements \IteratorAggregate<string, Route>
 */
final class RouteCollection implements \IteratorAggregate, \Countable
{
    /** @var array<string, Route> $routes */
    private array $routes = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->routes);
    }

    /**
     * @param string $name
     * @param Route $route
     * @return void
     */
    public function add(string $name, Route $route): void
    {
        $this->routes[$name] = $route;
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }


    /**
     * @param string $name
     * @return Route|null
     */
    public function get(string $name): ?Route
    {
        return $this->routes[$name] ?? null;
    }

    /**
     * @param self $collection
     * @return void
     */
    public function mergeCollection(self $collection): void
    {
        foreach ($collection->all() as $name => $route) {
            unset($this->routes[$name]);
            $this->routes[$name] = $route;
        }
    }
}
