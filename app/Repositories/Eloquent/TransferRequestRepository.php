<?php

namespace App\Repositories\Eloquent;

use App\Models\TransferRequests;
use App\Repositories\Interfaces\TransferRequestRepositoryInterface;

class TransferRequestRepository implements TransferRequestRepositoryInterface
{

    protected TransferRequests $model;
    public function __construct(TransferRequests $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->orderBy('requested_date', 'desc')->get();
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
        $transferRequest = $this->model->findOrFail($id);
        $transferRequest->update($data);
        return $transferRequest;
    }

    public function delete(int $id)
    {
        $transferRequest = $this->model->findOrFail($id);
        $transferRequest->delete();
        return $transferRequest;
    }
}
