<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function getAll();

    public function getById($id);

    public function getByItemId($itemId);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
