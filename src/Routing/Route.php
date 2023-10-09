<?php

namespace Nulldark\Routing;

class Route
{
    /** @var string $path */
    private string $path;
    /** @var string[] */
    private array $methods;
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

    public function compile(): CompiledRoute
    {
        if ($this->compiled === null) {
            $this->compiled = RouteCompiler::compile($this->getPath());
        }

        return $this->compiled;
    }
}
