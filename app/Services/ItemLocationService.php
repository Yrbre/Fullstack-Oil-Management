<?php

namespace App\Services;

use App\Models\IcTransInv;
use App\Repositories\Eloquent\ItemLocationRepository;
use App\Services\Interfaces\ItemLocationServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemLocationService implements ItemLocationServiceInterface
{
    protected ItemLocationRepository $itemLocationRepository;
    public function __construct(
        ItemLocationRepository $itemLocationRepository,
    ) {
        $this->itemLocationRepository = $itemLocationRepository;
    }

    public function getAll()
    {
        return $this->itemLocationRepository->getAll();
    }

    public function getAllGroupBy()
    {
        return $this->itemLocationRepository->getAllGroupBy();
    }

    public function getById(int $id)
    {
        return $this->itemLocationRepository->getById($id);
    }

    public function getByOrgnCode(string $orgnCode)
    {
        return $this->itemLocationRepository->getByOrgnCode($orgnCode);
    }

    public function create(array $data)
    {
        // Buat logika untuk menghitung exp date berdasarkan tanggal produksi yang diinputkan
        try {
            return DB::transaction(function () use ($data) {

                $bbQty = DB::table('item_locations')
                    ->where('item_id', $data['item_id'])
                    ->where('warehouse_id', $data['warehouse_id'])
                    ->sum('qty_weight');

                $data['production_date'] = $data['production_date'] . '-01';
                $data['exp_date'] = $this->calculateExpDate($data['production_date']);
                $itemLocation = $this->itemLocationRepository->create($data);
                $transDate = $itemLocation->received_date;

                $transCode = $this->generateTransCode();

                $dataTransaksi = [
                    'item_id' => $itemLocation->item_id,
                    'trans_date' => $transDate,
                    'doc_type' => 'PORC',
                    'trans_qty' => $itemLocation->qty_weight,
                    'orgn_code' => $itemLocation->orgn_code,
                    'whse_code' => $itemLocation->warehouse->tag,
                    'whse_loc'  => $itemLocation->warehouse->name,
                    'warehouse_tag' => $itemLocation->warehouse->tag,
                    'trans_code' => $transCode,
                    'warehouse_id' => $itemLocation->warehouse_id,
                    'bb_qty' => $bbQty,
                ];

                app(\App\Services\Interfaces\TransactionServiceInterface::class)
                    ->create($dataTransaksi, Auth()->user()->name);

                return $itemLocation;
            });
        } catch (\Exception $e) {
            Log::error('Error creating item location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data lokasi item: ' . $e->getMessage());
        }
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

    private function generateTransCode()
    {
        $prefix = 'PORC';
        $thn = now()->format('y');
        $bln = now()->format('m');
        $prefixCode = "{$prefix}-{$thn}{$bln}";

        return DB::transaction(function () use ($prefixCode) {
            $lastTrans = IcTransInv::where('trans_code', 'like', "{$prefixCode}%")
                ->lockForUpdate()
                ->orderBy('trans_code', 'desc')
                ->first();

            $nextNumber = $lastTrans
                ? ((int) substr($lastTrans->trans_code, -4)) + 1
                : 1;

            return $prefixCode . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
