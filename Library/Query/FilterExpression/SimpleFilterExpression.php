<?php

namespace Library\Query\FilterExpression;

use Library\Query\FilterExpression\Exceptions\UnsupportedColumnException;
use Library\Query\FilterExpression\Exceptions\UnsupportedOperationException;
use function PHPUnit\Framework\isEmpty;

/**
 * FilterExpression : filter expression that can be expressed in a query parameter or json
 * it lends itself a simple name since it does not do complex (or) logic , this is only (and) logic
 *
 * @author  Ahmad Baderkhan
 */
class SimpleFilterExpression
{
    use ToSQL;


    private array $filters;
    private SimpleFilterEnitity $model;

    private const DELIMIT_ARGS = "&&";
    private const DELIMIT_EXPR = "::";

    public const COLUMN = "col";
    public const VALUE = "val";
    public const OPERATION = "op";

    public const OP_EQ = "eq";
    public const OP_NE = "ne";
    public const OP_IN = "in";
    public const OP_NIN = "nin";
    public const OP_GT = "gt";
    public const OP_GTE = "gte";
    public const OP_LT = "lt";
    public const OP_LTE = "lte";
    public const OP_LIKE = "like";
    public const OP_NOT_LIKE = "notlike";

    private function __construct(array $filters, SimpleFilterEnitity $model)
    {
        $this->filters = $filters;
        $this->model = $model;
    }

    /**
     * Client Side supported operations
     */
    public const OPERATIONS_SUPPORTED = [
        self::OP_EQ,
        self::OP_NE,
        self::OP_IN,
        self::OP_NIN,
        self::OP_GT,
        self::OP_GTE,
        self::OP_LT,
        self::OP_LTE,
        self::OP_LIKE,
        self::OP_NOT_LIKE,
    ];

    public static function Empty(): SimpleFilterExpression
    {
        return new SimpleFilterExpression();
    }


    /**
     *
     * Will parse user input and attempt to create a filter expression
     * otherwise it will return an error message
     *
     * @param string $userInput
     * @param SimpleFilterEnitity $model
     * @return SimpleFilterExpressionOutput
     * @example
     *  $out = FilterExpression::FromQuery("some_col:eq:value");
     *  if ($out->HasError())
     *  {
     *      // do something
     *  }
     * $filterExp = $out->GetFilter()
     * $filterExp->toSQL()
     *
     */
    public static function FromQuery(string $userInput, SimpleFilterEnitity $model): SimpleFilterExpressionOutput
    {
        $msgs = array();
        $filters = array();

        $exprs = explode(self::DELIMIT_ARGS ,$userInput);

        foreach ($exprs as $expr) {
            if ($expr == "")
            {
                continue;
            }
            $ar = explode( self::DELIMIT_EXPR,$expr);

            if (sizeof($ar) != 3) {
                $msgs[] = "Your expression must have a col::op::value at " . $expr;
                continue;
            }
            $col = $ar[0];
            $op = $ar[1];
            $value = $ar[2];

            if (!self::isColumnSupported($model, $col)) {
                $msgs[] = "Column $col is not supported at $expr";
                continue;
            }

            if (!self::isOperationSupported($op)) {
                $msgs[] = "Operation $op is not supported at $expr, " .
                    "the following are the supported expressions " .
                    self::formatOpSupported();
                continue;
            }
            $value = self::parseValue($value, $op);
            $filters[] = [
                self::COLUMN => $col,
                self::OPERATION => $op,
                self::VALUE => $value
            ];
        }

        return SimpleFilterExpressionOutput::Init()
            ->_withFilter(new SimpleFilterExpression($filters, $model))
            ->_withErrMsgs($msgs);
    }

    private static function formatOpSupported(): string
    {
        $ops = self::OPERATIONS_SUPPORTED;
        return implode(",", $ops);
    }

    private static function isColumnSupported(SimpleFilterEnitity $model, string $col): bool
    {

        return in_array($col, $model->GetFilterableColumns());
    }

    private static function isOperationSupported(string $op): bool
    {
        return in_array($op, self::OPERATIONS_SUPPORTED);
    }

    private static function parseValue(string $value, $op): mixed
    {
        if (self::isMultiValueOp($op)) {
            return explode($value, ",");
        }
        if (strtolower($value) == "true") {
            return true;
        }
        if (strtolower($value) == "false") {
            return false;
        }

        return $value;
    }

    private static function isMultiValueOp($op): bool
    {
        $op = strtolower($op);
        return $op == self::OP_IN || $op == self::OP_NIN;
    }

    protected function _getFilters(): array
    {
        return $this->filters;
    }

    protected function _getModel(): SimpleFilterEnitity
    {
        return $this->model;
    }

    protected function _isSupportedCol(string $col, SimpleFilterEnitity $model): bool
    {
        return self::isColumnSupported($model, $col);
    }

    protected function _isSupportedOperation(string $op): bool
    {
        return self::isOperationSupported($op);
    }

    protected function _parseFilter(array $filter, $valueNeedsParsing = true): array
    {
        $col = $filter[SimpleFilterExpression::COLUMN];
        $val = $filter[SimpleFilterExpression::VALUE];
        $op = $filter [SimpleFilterExpression::OPERATION];
        if (!self::_isSupportedCol($col, $this->_getModel())) {
            throw new UnsupportedColumnException($col, json_encode($filter));
        }

        if (!self::_isSupportedOperation($op)) {
            throw new UnsupportedOperationException(
                $op,
                json_encode($filter),
                SimpleFilterExpression::OPERATIONS_SUPPORTED
            );
        }

        if (!$valueNeedsParsing) {
            $filter[SimpleFilterExpression::VALUE] = self::parseValue($val,$op);
        }
        return $filter;
    }

    public function WithFilter(array $filter): self
    {
        $this->filters[]=$this->_parseFilter($filter);
        return $this;
    }

}
