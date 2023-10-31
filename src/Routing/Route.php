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
 * @author Dominik Szamburski
 * @package Routing
 * @license LGPL-2.1
 * @version 0.1.0
 */
class Route
{
    /** @var string $path */
    private string $path;

    /** @var array<string, mixed> $defaults */
    private array $defaults;

    /** @var string[] */
    private array $methods;
    /** @var array<string, int|string> $args */
    private array $args = [];
    /** @var CompiledRoute|null $compiled */
    private ?CompiledRoute $compiled;

    /**
     * @param string               $path
     * @param array<string, mixed> $defaults
     * @param string[]             $methods
     */
    public function __construct(string $path, array $defaults = [], array $methods = [])
    {
        $this->setPath($path);
        $this->setDefaults($defaults);
        $this->setMethods($methods);
    }

    /**
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
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string|string[] $methods
     * @return $this
     */
    public function setMethods(string|array $methods): self
    {
        $this->methods = array_map('strtoupper', (array) $methods);
        $this->compiled = null;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Sets route defaults.
     *
     * @param array<string, mixed> $defaults
     * @return $this
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
     * Gets route defaults.
     *
     * @return array<string, mixed>
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * @param string $name
     * @param int|string $value
     * @return $this
     */
    public function setArg(string $name, int|string $value): self
    {
        $this->args[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return int|string|null
     */
    public function getArg(string $name): int|string|null
    {
        return $this->args[$name] ?? null;
    }

    /**
     * @return array<string, int|string>
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Compile a route.
     *
     * @return CompiledRoute
     */
    public function compile(): CompiledRoute
    {
        if ($this->compiled === null) {
            $this->compiled = RouteCompiler::compile($this->getPath());
        }

        return $this->compiled;
    }
}
