<?php

namespace Library\Query\FilterExpression;

class SQLOutput{
    private string $sql;
    private array $args;

    public static function Init() : SQLOutput
    {
        return new SQLOutput();
    }
    public function _withSQL($sql) : self
    {
        $this->sql=$sql;
        return $this;
    }

    public function _withArgs($args) : self
    {
        $this->args = $args;
        return $this;
    }

    public function GetSQL() : string
    {
        return $this->sql;
    }

    public function GetArgs() : array
    {
        return $this->args;
    }
}
