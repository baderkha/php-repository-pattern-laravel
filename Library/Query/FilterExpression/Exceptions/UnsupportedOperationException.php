<?php

namespace Library\Query\FilterExpression\Exceptions;

use Library\Query\FilterExpression\SimpleFilterExpression;

/**
 * Thrown when simple filter expression trait has an operation that is not supported
 */
class UnsupportedOperationException extends \RuntimeException
{
    private string $operation;
    private string $where;

    public function __construct(string $operation,string $where,string $supportedFilters)
    {
        $this->operation =  $operation;
        $this->where = $where;
        parent::__construct($this->format($operation,$where,$supportedFilters));
    }

    function format($operation,$where,$supportedFilters) : string
    {
        return "Operation $operation unsupported in $where . you should be using the following filters$supportedFilters";
    }
}
