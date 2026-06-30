<?php

namespace App\Repositories\Eloquent;

use App\Models\Departments;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected Departments $model;
    public function __construct(
        Departments $model
    ) {
        $this->model = $model;
    }


    public function getAll()
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $department = $this->getById($id);
        $department->update($data);
        return $department;
    }

    public function delete(int $id)
    {
        $department = $this->getById($id);
        $department->delete();
        return $department;
    }
}
