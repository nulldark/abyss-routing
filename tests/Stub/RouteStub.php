<?php

namespace Nulldark\Tests\Stub;

use Nulldark\Routing\Route;

class RouteStub extends Route
{
    public function __construct()
    {
        parent::__construct([], '/', CallbackStub::class);
    }
}
