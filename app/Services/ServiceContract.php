<?php
namespace App\Services;
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/23/17
 * Time: 8:34 PM
 */

interface ServiceContract
{
    public function find($id);

    public function delete($id);

    public function store($data);

    public function update($data,$id);

    public function filter($data,$limit = 10);

    public function datatables($param =  array());

    public function meta();
}