<?php

namespace App\Services;

use App\Models\IcTransInv;
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
        $data['inactive_ind'] = 0;
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

    public function getTransactionByMonth($id, int $month, int $year)
    {
        $bln = str_pad($month, 2, '0', STR_PAD_LEFT);
        $thn = (string) $year;

        return IcTransInv::where('item_id', $id)
            ->where('bln', $bln)
            ->where('thn', $thn)
            ->orderBy('trans_date', 'asc')
            ->get();
    }
}
