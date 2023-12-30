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

namespace Abyss\Routing;

/**
 * @package Abyss\Routing
 * @since 0.1.0
 */
class Route
{
    /**
     * The HTTP methods for this route.
     *
     * @var string[] $methods
     */
    protected array $methods = [];

    /**
     * The path for this route.
     *
     * @var string $path
     */
    protected string $path;

    /**
     * The route handler.
     *
     * @var \Closure|string|array<class-string, string> $callback
     */
    protected \Closure|string|array $callback;

    /**
     * The compiled version of the route.
     *
     * @var CompiledRoute|null $compiled
     */
    private ?CompiledRoute $compiled;

    /**
     * The default parameter values.
     *
     * @var mixed[] $defaults
     */
    private array $defaults = [];

    /**
     * The path parameters values.
     *
     * @var array<string, mixed> $parameters
     */
    private array $parameters = [];

    /**
     * @param string[] $methods
     * @param string $path
     * @param \Closure|string|array<class-string, string> $callback
     */
    public function __construct(array $methods, string $path, \Closure|string|array $callback)
    {
        $this->setPath($path);
        $this->setMethods($methods);
        $this->setCallback($callback);

        $this->compiled = null;
    }

    /**
     * Sets a route HTTP methods.
     *
     * @param string[] $methods
     * @return $this
     */
    public function setMethods(array $methods): self
    {
        $this->compiled = null;
        $this->methods = [];

        foreach ($methods as $method) {
            $this->methods[] = strtoupper($method);
        }

        return $this;
    }

    /**
     * Sets a route path.
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = '/' . ltrim(trim($path), '/');
        $this->compiled = null;

        return $this;
    }

    /**
     * Sets as route handler.
     *
     * @param \Closure|string|array<class-string, string> $callback
     * @return $this
     */
    public function setCallback(\Closure|string|array $callback): self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Gets HTTP methods for currenet route.
     *
     * @return string[]
     */
    public function methods(): array
    {
        return $this->methods;
    }

    /**
     * Gets a route pattern.
     *
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Gets a route handler.
     *
     * @return \Closure|string|array<class-string, string|null>
     */
    public function callback(): \Closure|string|array
    {
        return $this->callback;
    }

    /**
     * Sets new default parameter for route.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return self
     */
    public function setDefault(string $name, mixed $default): self
    {
        $this->defaults[$name] = $default;
        $this->compiled = null;

        return $this;
    }

    /**
     * Sets given defaults into route.
     *
     * @param array<string, mixed> $defaults
     *
     * @return self
     */
    public function setDefaults(array $defaults): self
    {
        $this->defaults = [];

        foreach ($defaults as $name => $default) {
            $this->defaults[$name] = $default;
        }

        $this->compiled = null;

        return $this;
    }

    /**
     * Checks if the indicated parameter exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasDefault(string $name): bool
    {
        return \array_key_exists($name, $this->defaults);
    }

    /**
     * Gets a default parameters.
     *
     * @return mixed[]
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * Gets a path parameters.
     *
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Gets path parameter.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return null|mixed
     */
    public function getParameter(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Sets a new path parameter.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function setParameter(string $key, mixed $value): self
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * Compiles a current route.
     *
     * @return CompiledRoute
     */
    public function compiled(): CompiledRoute
    {
        if ($this->compiled === null) {
            $this->compiled = RouteCompiler::compile($this->path());
        }

        return $this->compiled;
    }
}
