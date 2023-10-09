<?php

namespace Nulldark\Routing;

final class CompiledRoute
{
    public function __construct(
        private string $regex,
        private array $variables,
        private array $tokens
    ) {
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }
}
