<?php

namespace Nulldark\Routing;

class Route
{
    private string $path;

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = '/' . ltrim(trim($path), '/');

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
