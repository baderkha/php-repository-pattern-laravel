<?php

namespace Library\Query\FilterExpression;

/**
 * Output when you call to generate a new filter expression
 */
class SimpleFilterExpressionOutput {
    private ?SimpleFilterExpression $filter = null;
    private array $msg = array();
    private bool $hasError = false;

    public static function Init() : self
    {
        return new SimpleFilterExpressionOutput();
    }

    public function _withFilter($filter) : self
    {
        $this->filter = $filter;
        return $this;
    }

    public function _withErrMsgs($msg) : self
    {
        $this->msg = $msg;
        if (!empty($this->msg) || !empty($msg))
        {
            $this->hasError = true;
        }
        return $this;
    }

    /**
     * Returns the simple filter expression
     * @return SimpleFilterExpression
     */
    public function GetFilter() : SimpleFilterExpression
    {
        return $this->filter;
    }

    /**
     * Returns the simple filter expression
     */
    public function HasError() : bool
    {
        return $this->hasError;
    }

    public function OutputError() : string
    {
        if ($this->HasError())
        {
            return "\n -". implode("\n -",$this->msg);
        }
        return "";
    }

    public function ErrorList() : array
    {
        return $this->msg;
    }
}


