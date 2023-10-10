<?php

namespace Nulldark\Routing;

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

    public function compile(): CompiledRoute
    {
        if ($this->compiled === null) {
            $this->compiled = RouteCompiler::compile($this->getPath());
        }

        return $this->compiled;
    }
}
