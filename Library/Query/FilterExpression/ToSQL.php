<?php
namespace Library\Query\FilterExpression;

use Library\Query\FilterExpression\Exceptions\UnsupportedColumnException;
use Library\Query\FilterExpression\Exceptions\UnsupportedOperationException;

/**
 * Attach this to the simple filter expression class
 * This allows it to get access for outputting sql values
 */
trait ToSQL
{
    abstract protected function _getFilters() : array;
    abstract protected function _parseFilter(array $filter, $valueNeedsParsing = true) : array;


    private array $filterMapping = [
        SimpleFilterExpression::OP_EQ       => "= ?",
        SimpleFilterExpression::OP_NE       => "<> ?",
        SimpleFilterExpression::OP_IN       => "IN(?)",
        SimpleFilterExpression::OP_NIN      => "NOT IN (?)",
        SimpleFilterExpression::OP_GT       => "> ?",
        SimpleFilterExpression::OP_GTE      => ">= ?",
        SimpleFilterExpression::OP_LT       => "< ?",
        SimpleFilterExpression::OP_LTE      => "<= ?",
        SimpleFilterExpression::OP_LIKE     => "LIKE ?",
        SimpleFilterExpression::OP_NOT_LIKE => "NOT LIKE ?",
    ];

    /**
     * @return SQLOutput
     * @throws UnsupportedColumnException | UnsupportedOperationException
     */
    public function ToSQL() : SQLOutput
    {
        $sqlAr = array();
        $args = array();
        $filterExpr = $this->_getFilters();
        foreach ($filterExpr as $f)
        {
            $f = $this->_parseFilter($f,false);

            $col = $f[SimpleFilterExpression::COLUMN];
            $val = $f[SimpleFilterExpression::VALUE];
            $op  = $f[SimpleFilterExpression::OPERATION];

            $op = $this->filterMapping[$op];

            $sqlAr[] = "$col $op";
            $args[] = $val;
        }

        return SQLOutput::Init()
            ->_withArgs($args)
            ->_withSQL(empty($sqlAr) ? "1=1" :implode(" AND ",$sqlAr));
    }
}
