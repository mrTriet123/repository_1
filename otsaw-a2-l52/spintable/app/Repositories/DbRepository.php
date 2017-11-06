<?php
namespace App\Repositories;

abstract class DbRepository
{
    public function getById($id)
    {
        return $this->model->find($id);
    }


}