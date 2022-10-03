<?php
namespace Library\Store\Repository;

class ModifyResponse {
    public int $rowsModified;
    public bool $isSuccess;

    public function __construct() {
        $this->rowsModified = 0;
        $this->isSuccess = false;
    }

    public static function Init(){
        return new ModifyResponse();
    }

    public function WithRowsModified(int $r) : self {
        $this->rowsModified = $r;
        return $this;
    }

    public function WithIsSuccess(bool $s) : self {
        $this->isSuccess = $s;
        return $this;
    }
}


