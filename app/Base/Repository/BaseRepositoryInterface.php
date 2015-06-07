<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 5/30/2015
 * Time: 11:26 AM
 */

namespace app\Base\Repository;


interface BaseRepositoryInterface {

    public function errors();

    public function all(array $related = null);

    public function get($id, array $related = null);

    public function getWhere($column, $value, array $related = null);


    public function create(array $data);

    public function update(array $data);

    public function delete($id);

    public function deleteWhere($column, $value);
}