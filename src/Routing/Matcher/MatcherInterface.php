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

namespace Nulldark\Routing\Matcher;

use Nulldark\Routing\Exception\MethodNotAllowedException;
use Nulldark\Routing\Exception\RouteNotFoundException;
use Nulldark\Routing\RouteMatch;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Dominik Szamburski
 * @package Routing
 * @subpackage Matcher
 * @license LGPL-2.1
 * @version 0.1.0
 */
interface MatcherInterface
{
    /**
     * Match a given request with a set of routes.
     *
     * @param ServerRequestInterface $request
     * @return RouteMatch
     *
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function matchRequest(ServerRequestInterface $request): RouteMatch;
}
