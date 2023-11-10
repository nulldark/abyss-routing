<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of nulldark/routing
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

namespace Nulldark\Routing;

/**
 * @package Nulldark\Routing
 * @since 0.1.0
 */
class Route
{
    /**
     * The HTTP methods for this route.
     *
     * @var string[] $methods
     */
    public array $methods = [];

    /**
     * The path for this route.
     *
     * @var string $path
     */
    public string $path;

    /**
     * The route handler.
     *
     * @var \Closure|string
     */
    public \Closure|string $callback;

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
     * @param string[] $methods
     * @param string $path
     * @param \Closure|string $callback
     */
    public function __construct(array $methods, string $path, \Closure|string $callback)
    {
        $this->setPath($path);
        $this->setMethods($methods);
        $this->setCallback($callback);
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
     * @param \Closure|string $callback
     * @return $this
     */
    public function setCallback(\Closure|string $callback): self
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
     * @return \Closure|string
     */
    public function callback(): \Closure|string
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
