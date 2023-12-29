<?php

namespace Abyss\Tests\Stub;

use Abyss\Routing\Route;

class RouteStub extends Route
{
    public function __construct()
    {
        parent::__construct([], '/', CallbackStub::class);
    }
}
