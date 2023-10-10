<?php

namespace Nulldark\Routing\Matcher;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;

class Matcher implements MatcherInterface
{
    /** @var string[] $allow */
    private array $allow;

    private ?ServerRequestInterface $request = null;

    public function __construct(
        protected RouteCollection $routes
    ) {
    }

    /**
     * @inheritDoc
     */
    public function matchRequest(ServerRequestInterface $request): Route
    {
        $this->request = $request;

        $ret = $this->match($request->getUri()->getPath());

        $this->request = null;

        return $ret;
    }

    /**
     * @param string $pathinfo
     * @return Route
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    protected function match(string $pathinfo): Route
    {
        $this->allow = [];

        $pathinfo = '/' . \ltrim(\trim($pathinfo), '/');

        if ($route = $this->matchRoute(\rawurldecode($pathinfo) ?: '/', $this->routes)) {
            return $route;
        }

        if (\count($this->allow) > 0) {
            throw new MethodNotAllowedException(
                \array_unique($this->allow)
            );
        }

        throw new RouteNotFoundException(sprintf("No route found for '%s'", $pathinfo), 404);
    }

    /**
     * @param string $pathinfo
     * @param RouteCollection $routes
     * @return Route|null
     */
    protected function matchRoute(string $pathinfo, RouteCollection $routes): ?Route
    {
        if ('HEAD' === $method = $this->request->getMethod()) {
            $method = 'GET';
        }

        foreach ($routes as $name => $route) {
            $compiledRoute = $route->compile();
            $requiredMethods = $route->getMethods();

            if ($requiredMethods && !\in_array($method, $requiredMethods)) {
                $this->allow = \array_merge($this->allow, $requiredMethods);
                continue;
            }

            $regex = $compiledRoute->getRegex();

            $pos = strrpos($regex, '$');
            $hasTrailingSlash = '/' === $regex[$pos - 1];
            $regex = substr_replace($regex, '/?$', $pos - $hasTrailingSlash, 1 + $hasTrailingSlash);

            if (!preg_match($regex, $pathinfo, $matches)) {
                continue;
            }

            foreach ($matches as $key => $match) {
                if (is_numeric($key) || $match === null) {
                    continue;
                }

                $route->setArg($key, $match);
            }

            return $route;
        }

        return null;
    }
}
