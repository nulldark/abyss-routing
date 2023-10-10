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
    /** @var string[] */
    private array $methods;
    /** @var array<string, mixed> $args */
    private array $args = [];
    /** @var CompiledRoute|null $compiled */
    private ?CompiledRoute $compiled;

    public function __construct(string $path, array $methods = [])
    {
        $this->setPath($path);
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
     * @param string|array $methods
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
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setArg(string $name, mixed $value): self
    {
        $this->args[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getArg(string $name): mixed
    {
        return $this->args[$name] ?? null;
    }

    /**
     * @return array
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
