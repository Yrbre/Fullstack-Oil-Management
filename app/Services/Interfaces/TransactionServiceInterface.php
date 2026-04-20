<?php

namespace App\Services\Interfaces;

interface TransactionServiceInterface
{
    public function getAll();

    public function getById($id);

    public function getByItemId($itemId);

    public function create(array $data, string $createdBy);

    public function update($id, array $data, string $updatedBy);

    public function delete($id);
}
