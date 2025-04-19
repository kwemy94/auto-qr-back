<?php

namespace App\Repositories;

abstract class ResourceRepository
{

    protected $model;

    public function getPaginate($n = null)
    {
        if ($n) {
            return $this->model->paginate($n);
        }
        $this->model->get();
    }

    public function store($inputs)
    {
        return $this->model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function update($id, $inputs)
    {
        return $this->getById($id)->update($inputs);
    }

    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }
}
