<?php

namespace Library\Store\Repository;

/**
 * CrudRepository great for caching / simple requirements
 */
interface CrudRepository
{
    public function findAll(string $id);
    public function findById(string $id);
    public function deleteById(string $id) : bool;
    public function create($model) : bool;
    public function update($model) : bool;
}
