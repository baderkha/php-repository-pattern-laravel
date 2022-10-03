<?php

namespace Library\Query\Pagination;

/**
 * Pagination Object
 * @author Ahmad Baderkhan
 */
class Pagination
{
    public int $page;
    public int $size;

    public const DEFAULT_SIZE = 10;

    public function __construct()
    {

    }

    public static function FromUserInput(string $page,string $size) : self
    {
        $pageInt = intval($page);
        $sizeInt = intval($size);
        return self::init()
            ->WithPage($pageInt === 0 ? 0 : $pageInt)
            ->WithSize($sizeInt === 0 ? self::DEFAULT_SIZE : $sizeInt);
    }

    private static function init() : self
    {
        return new Pagination();
    }

    public function WithPage(int $page) : self
    {
        $this->page = $page;
        return $this;
    }

    public function WithSize(int $size) : self
    {
        $this->size = $size;
        return $this;
    }
}
