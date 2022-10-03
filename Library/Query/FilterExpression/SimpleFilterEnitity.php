<?php

namespace Library\Query\FilterExpression;

/**
 * SimpleFilterEnitity
 * Contract that allows a class to say that it can support filtering
 *
 * @author  Ahmad Baderkhan
 */
interface SimpleFilterEnitity
{
    /**
     * Attach this to your model you can expose what columns have ability to be filtered
     * @return array
     */
    public function GetFilterableColumns() : array;

    /**
     * @return string
     */
    public function TableName() : string;
}
