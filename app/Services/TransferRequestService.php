<?php

namespace App\Services;

use App\Repositories\Eloquent\TransferRequestRepository;
use App\Services\Interfaces\TransferRequestServiceInterface;

class TransferRequestService implements TransferRequestServiceInterface
{
    protected TransferRequestRepository $transfer_request_repository;
    public function __construct(
        TransferRequestRepository $transfer_request_repository
    ) {
        $this->transfer_request_repository = $transfer_request_repository;
    }

    public function getAll()
    {
        return $this->transfer_request_repository->getAll();
    }

    public function getById(int $id)
    {
        return $this->transfer_request_repository->getById($id);
    }

    public function create(array $data)
    {
        return $this->transfer_request_repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->transfer_request_repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->transfer_request_repository->delete($id);
    }
}
