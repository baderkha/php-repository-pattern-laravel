<?php

namespace Library\Query\Pagination;

class PaginatedCollection
{
    public mixed $data;
    public int $currentPage;
    public int $currentSize;
    public int $totalRecords;
    public int $pagesRemaining;
    public int $nextPage;
    public int $lastPage;

    public function __construct()
    {

    }

    public static function Init() : self
    {
        return new PaginatedCollection();
    }

    public function withData(mixed $data) : self
    {
        $this->data = $data;
        return $this;
    }

    public function withCurrentSize($currentSize) : self
    {
        $this->currentSize = $currentSize;
        return $this;
    }
    public function withCurrentPage($currentPage) : self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function withTotalRecords($totalRecords) : self
    {
        $this->totalRecords = $totalRecords;
        return $this;
    }

    public function withPagesRemaining($pagesRemaining) : self
    {
        $this->pagesRemaining = $pagesRemaining;
        return $this;
    }

    public function withNextPage($nextPage) : self
    {
        $this->nextPage = $nextPage;
        return $this;
    }

    public function withLastPage($lastPage) : self
    {
        $this->lastPage = $lastPage;
        return $this;
    }


}
