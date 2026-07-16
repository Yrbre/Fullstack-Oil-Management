<?php

namespace App\Repositories\Eloquent;

use App\Models\Warehouses;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;


class WarehouseRepository implements WarehouseRepositoryInterface
{
    protected Warehouses $model;

    public function __construct(Warehouses $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->orderBy('name')->get();
    }


    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByOrgnCode(string $department_id)
    {
        return $this->model->where('department_id', $department_id)->orderBy('name')->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $warehouse = $this->model->findOrFail($id);
        $warehouse->update($data);
        return $warehouse;
    }

    public function delete(int $id)
    {
        $warehouse = $this->model->findOrFail($id);
        $warehouse->delete();
        return $warehouse;
    }
}
