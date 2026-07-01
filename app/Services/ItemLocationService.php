<?php

namespace App\Services;

use App\Repositories\Eloquent\ItemLocationRepository;
use App\Services\Interfaces\ItemLocationServiceInterface;

class ItemLocationService implements ItemLocationServiceInterface
{
    protected ItemLocationRepository $itemLocationRepository;
    public function __construct(
        ItemLocationRepository $itemLocationRepository
    ) {
        $this->itemLocationRepository = $itemLocationRepository;
    }

    public function getAll()
    {
        return $this->itemLocationRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->itemLocationRepository->getById($id);
    }

    public function create(array $data)
    {
        // Buat logika untuk menghitung exp date berdasarkan tanggal produksi yang diinputkan
        $data['production_date'] = $data['production_date'] . '-01';
        $data['exp_date'] = $this->calculateExpDate($data['production_date']);
        return $this->itemLocationRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['production_date'])) {
            $data['production_date'] = $data['production_date'] . '-01'; // Format production_date ke Y-m-d
            $data['exp_date'] = $this->calculateExpDate($data['production_date']);
        }
        return $this->itemLocationRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->itemLocationRepository->delete($id);
    }

    private function calculateExpDate(string $productionDateStr)
    {
        $productionDate = \DateTime::createFromFormat('Y-m-d', $productionDateStr);
        $errors = \DateTime::getLastErrors();
        if (!$productionDate || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            throw new \InvalidArgumentException("Tanggal produksi tidak valid...");
        }
        $expDate = clone $productionDate;
        $expDate->modify('+1 year'); // Menambahkan 1 tahun ke tanggal produksi
        return $expDate->format('Y-m-d');
    }
}
