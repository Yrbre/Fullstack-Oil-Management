<?php

namespace App\Services\Interfaces;

interface TransactionServiceInterface
{
    public function getAll();

    public function getById(int $id);

    public function getByItemId(string $itemId);

    public function create(array $data, string $createdBy);

    public function update(int $id, array $data, string $updatedBy);

    public function delete(int $id);
}
