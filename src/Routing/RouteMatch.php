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

use Psr\Container\ContainerInterface;

/**
 * @author Dominik Szamburski
 * @package Routing
 * @license LGPL-2.1
 * @since 1.0.0
 */
final class RouteMatch
{
    /** @var array<array-key, mixed> $defaults */
    protected array $defaults;

    /** @var array<array-key, int|string> $params */
    protected array $params;

    protected ContainerInterface $container;

    /**
     * @param array<array-key, mixed> $defaults
     * @param array<array-key, int|string> $params
     */
    public function __construct(array $defaults = [], array $params = [])
    {
        $this->setDefaults($defaults);
        $this->setParameters($params);
    }

    /**
     * @param array<array-key, mixed> $defaults
     * @return $this
     */
    public function setDefaults(array $defaults): self
    {
        foreach ($defaults as $key => $default) {
            $this->defaults[$key] = $default;
        }

        return $this;
    }

    /**
     * Gets a route defaults.
     *
     * @return array<array-key, mixed>
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * Gets a route default.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getDefault(string $key, mixed $default = null): mixed
    {
        return $this->defaults[$key] ?? $default;
    }


    /**
     * @param array<array-key, int|string> $params
     * @return $this
     */
    public function setParameters(array $params): self
    {
        foreach ($params as $key => $param) {
            if (is_numeric($key) || $param === null) {
                continue;
            }

            $this->params[$key] = $param;
        }

        return $this;
    }

    /**
     * Gets a route parameters.
     *
     * @return array<array-key, int|string>
     */
    public function getParameters(): array
    {
        return $this->params;
    }

    /**
     *  Gets a route parameter.
     *
     * @param string $key
     * @param string|null $default
     *
     * @return string|int|null
     */
    public function getParameter(string $key, string $default = null): null|string|int
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Sets DI Container.
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Gets DI Container.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
