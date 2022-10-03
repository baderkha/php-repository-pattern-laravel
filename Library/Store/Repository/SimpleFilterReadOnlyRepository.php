<?php


namespace Library\Store\Repository;

use Library\Query\FilterExpression\SimpleFilterExpression;
use Library\Query\Pagination\PaginatedCollection;
use Library\Query\Pagination\Pagination;

/**
 * SimpleFilterReadOnlyRepository a read only repository that supports simple filter expressions from the client side
 */
interface SimpleFilterReadOnlyRepository
{
    function FindAllWithFilter(SimpleFilterExpression $filter) : array;
    function FindAllPaginatedAndFilter(Pagination $pagination,SimpleFilterExpression $filter) : PaginatedCollection;
    function FindAllPaginated(Pagination $pagination) : PaginatedCollection;
}



