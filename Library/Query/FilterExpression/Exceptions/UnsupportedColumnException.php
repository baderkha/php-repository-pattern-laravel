<?php

namespace Library\Query\FilterExpression\Exceptions;

/**
 * thrown when a column does not exist in the model
 */
class UnsupportedColumnException extends \RuntimeException
{
    private string $column;
    private string $where;

    public function __construct($column,$where)
    {
        $this->column = $column;
        $this->where = $where;
        parent::__construct($this->format($column,$where));
    }

    function format($column,$where) : string
    {
        return "Column $column unsupported in $where";
    }
}
