<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function getByItemId(int $itemId);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
