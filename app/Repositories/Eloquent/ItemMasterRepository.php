<?php

namespace App\Repositories\Eloquent;

use App\Models\IcItemMst;
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;


class ItemMasterRepository implements ItemMasterRepositoryInterface
{
    protected $model;

    public function __construct(IcItemMst $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->findOrfail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->model->findOrfail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->model->findOrfail($id);
        return $item->delete();
    }
}
