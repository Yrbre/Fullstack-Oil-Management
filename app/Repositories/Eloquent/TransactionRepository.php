<?php

namespace App\Repositories\Eloquent;

use App\Models\IcTrnasInv;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(IcTrnasInv $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByItemId($itemId)
    {
        return $this->model->with('item')
            ->where('item_id', $itemId)
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transaction = $this->model->findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = $this->model->findOrFail($id);
        $transaction->update(['status' => 'deleted']);
        return $transaction;
    }
}
