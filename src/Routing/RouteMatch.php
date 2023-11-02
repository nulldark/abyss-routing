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
 * @since 1.0.0
 */
final class RouteMatch
{
    /** @var array $defaults */
    protected array $defaults;

    /** @var array $params */
    protected array $params;

    /**
     * @param array $defaults
     * @param array $params
     */
    public function __construct(array $defaults = [], array $params = []) {
        $this->setDefaults($defaults);
        $this->setParameters($params);
    }

    /**
     * @param array $defaults
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
     * @param array $params
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
}