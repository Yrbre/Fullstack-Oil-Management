<?php

namespace App\Services\Interfaces;

interface ItemLocationServiceInterface
{
    public function getAll();

    public function getAllGroupBy();

    public function getById(int $id);

    public function getByOrgnCode(string $orgnCode);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
