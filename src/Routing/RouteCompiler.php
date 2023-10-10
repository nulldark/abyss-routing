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
 * @internal
 */
final class RouteCompiler
{
    public const VARIABLE_MAXIMUM_LENGTH = 32;

    /**
     * @param string $pattern
     * @return CompiledRoute
     */
    public static function compile(string $pattern): CompiledRoute
    {
        $tokens = [];
        $matches = [];
        $variables = [];
        $pos = 0;

        preg_match_all('#\{(!)?([\w\x80-\xFF]+)}#', $pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($matches as $match) {
            $varName = $match[2][0];
            if (preg_match('/^\d/', $varName)) {
                throw new \DomainException("Variable name '$varName' cannot start with a digit in route pattern '$pattern'.");
            }
            if (in_array($varName, $variables)) {
                throw new \LogicException(
                    sprintf('Variable "%s" is already defined in route pattern "%s".', $varName, $pattern)
                );
            }

            if (strlen($varName) > self::VARIABLE_MAXIMUM_LENGTH) {
                throw new \DomainException(
                    sprintf(
                        'Variable name "%s" cannot be longer than %d characters in route pattern "%s".
                            Please use a shorter name.',
                        $varName,
                        self::VARIABLE_MAXIMUM_LENGTH,
                        $pattern
                    )
                );
            }

            $precedingText = substr($pattern, $pos, $match[0][1] - $pos);
            $precedingChar = !strlen($precedingText) ? '' : substr($precedingText, -1);

            $pos = $match[0][1] + strlen($match[0][0]);

            if ('' !== $precedingChar) {
                $tokens[] = ['text', $precedingText];
            }

            $regexp = sprintf(
                '[^%s%s]+',
                preg_quote('/', '/'),
                ''
            );

            $tokens[] = ['variable', $regexp, $varName];
            $variables[] = $varName;
        }

        if ($pos < \strlen($pattern)) {
            $tokens[] = ['text', substr($pattern, $pos)];
        }

        $regexp = '';
        for ($i = 0, $nbToken = count($tokens); $i < $nbToken; $i++) {
            $regexp .= self::computeRegex($tokens, $i);
        }

        if ($regexp === '') {
            $regexp = '\/';
        }

        $regexp = '#^' . $regexp . '$#sD';

        return new CompiledRoute(
            $regexp,
            $variables,
            $tokens
        );
    }

    /**
     * @param array<int, array<int, string>> $tokens
     * @param int $index
     * @return string
     */
    private static function computeRegex(array $tokens, int $index): string
    {
        $token = $tokens[$index];

        if ('variable' === $token[0]) {
            if (0 === $index) {
                return sprintf('(?P<%s>%s)?', $token[3], $token[2]);
            }

            return sprintf('(?P<%s>%s)', $token[2], $token[1]);
        }

        return preg_quote($token[1], '/');
    }
}
