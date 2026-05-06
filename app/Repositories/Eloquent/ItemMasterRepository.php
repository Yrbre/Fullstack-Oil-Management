<?php

namespace App\Repositories\Eloquent;

use App\Models\IcItemMst;
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;


class ItemMasterRepository implements ItemMasterRepositoryInterface
{
    protected IcItemMst $model;

    public function __construct(IcItemMst $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->findOrfail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $item = $this->model->findOrfail($id);
        $item->update($data);
        return $item;
    }

    public function delete(int $id)
    {
        $item = $this->model->findOrfail($id);
        return $item->delete();
    }

    public function getByOrgnCode(string $orgnCode)
    {
        return $this->model->when($orgnCode !== 'IT', function ($q) use ($orgnCode) {
            $q->where('orgn_code', $orgnCode);
        })
            ->orderBy('item_no')
            ->get();
    }
}
