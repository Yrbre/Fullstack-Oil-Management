<?php

namespace App\Services\Interfaces;

interface ItemMasterServiceInterface
{
    public function getAll();

    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getTransactionByMonth(int $id, int $month, int $year);

    public function getByOrgnCode(string $orgnCode);
}
