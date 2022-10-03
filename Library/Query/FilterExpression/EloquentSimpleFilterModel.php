<?php

namespace Library\Query\FilterExpression;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EloquentSimpleFilterModel extends Model implements SimpleFilterEnitity
{
    public function GetFilterableColumns(): array
    {
        $all = Schema::getColumnListing($this->TableName());

        return array_filter($all,function ($v,$k){
            return !in_array($v,$this->getHidden());
        },ARRAY_FILTER_USE_BOTH);
    }

    public function TableName(): string
    {
       return $this->getTable();
    }
}
