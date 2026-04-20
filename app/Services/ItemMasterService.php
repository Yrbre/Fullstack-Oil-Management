<?php

namespace App\Services;

use App\Repositories\Eloquent\ItemMasterRepository;
use App\Services\Interfaces\ItemMasterServiceInterface;

class ItemMasterService implements ItemMasterServiceInterface
{

    protected $itemMasterRepository;

    public function __construct(ItemMasterRepository $itemMasterRepository)
    {
        $this->itemMasterRepository = $itemMasterRepository;
    }

    public function getAll()
    {
        return $this->itemMasterRepository->getAll();
    }

    public function getById($id)
    {
        return $this->itemMasterRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->itemMasterRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->itemMasterRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->itemMasterRepository->delete($id);
    }
}
