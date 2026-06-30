<?php

namespace App\Services;

use App\Repositories\Eloquent\DepartmentRepository;
use App\Services\Interfaces\DepartmentServiceInterface;

class DepartmentService implements DepartmentServiceInterface
{
    protected DepartmentRepository $departmentRepository;

    public function __construct(
        DepartmentRepository $departmentRepository
    ) {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAll()
    {
        return $this->departmentRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->departmentRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->departmentRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->departmentRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->departmentRepository->delete($id);
    }
}
