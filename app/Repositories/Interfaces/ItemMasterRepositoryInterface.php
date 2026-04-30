<?php

namespace App\Repositories\Interfaces;

interface ItemMasterRepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getByOrgnCode(string $orgnCode);
}
