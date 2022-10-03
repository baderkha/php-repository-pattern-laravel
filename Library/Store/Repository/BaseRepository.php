<?php
namespace Library\Store\Repository;
/**
 * BaseRepository : A basic repository crud interface for you to extend
 *
 * @author  Ahmad Baderkhan
 * @version 1
 *
 * @Note the find methods have returns , but because php does not allow generics
 * they look like voids
 */
interface BaseRepository extends CrudRepository
{
    public function findById(string $id);
    public function findIn(array $id);
    public function createMany(array $models) : ModifyResponse;
    public function updateMany(array $models): ModifyResponse;
}
