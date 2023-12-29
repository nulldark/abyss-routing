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
final readonly class CompiledRoute
{
    public function __construct(
        /** @var string $regex */
        private string $regex,
        /** @var array<int<0, max>, int|string> $variables */
        private array $variables,
        /** @var array<int<0, max>, array<int, string>> $tokens */
        private array $tokens
    ) {
    }

    /**
     * Gets a regex path.
     *
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * Gets all variables of defined route.
     *
     * @return array<int<0, max>, int|string>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Gets all route tokens.
     *
     * @return array<int<0, max>, array<int, string>>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
