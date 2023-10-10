<?php

namespace Nulldark\Routing\Exception;

class MethodNotAllowedException extends \Exception
{
    /**
     * @param string[] $methods
     */
    public function __construct(array $methods)
    {
        parent::__construct(
            sprintf(
                "Allowed methods for route '%s'.",
                implode(
                    '|',
                    array_map('strtoupper', $methods)
                )
            ),
            405
        );
    }
}
