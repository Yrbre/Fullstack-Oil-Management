<?php

namespace App\Repositories\Eloquent;

use App\Models\ItemLocations;
use App\Repositories\Interfaces\ItemLocationRepositoryInterface;

class ItemLocationRepository implements ItemLocationRepositoryInterface
{
    protected ItemLocations $model;
    public function __construct(
        ItemLocations $model
    ) {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->orderBy('received_date', 'desc')->get();
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
        $itemLocation = $this->model->findOrFail($id);
        $itemLocation->update($data);
        return $itemLocation;
    }

    public function delete(int $id)
    {
        $itemLocation = $this->model->findOrFail($id);
        return $itemLocation->delete();
    }
}
