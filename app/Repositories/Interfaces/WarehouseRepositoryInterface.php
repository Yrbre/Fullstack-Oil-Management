<?php

namespace App\Repositories\Interfaces;

interface WarehouseRepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function getByOrgnCode(string $orgn_code);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
