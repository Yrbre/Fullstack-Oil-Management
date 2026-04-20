<?php

namespace App\Services;

use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{
    protected $transactionRepository;
    protected $itemMasterRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        ItemMasterRepositoryInterface $itemMasterRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->itemMasterRepository = $itemMasterRepository;
    }

    public function getAll()
    {
        return $this->transactionRepository->getAll();
    }

    public function getById($id)
    {
        return $this->transactionRepository->getById($id);
    }

    public function getByItemId($itemId)
    {
        return $this->transactionRepository->getByItemId($itemId);
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function ($data) {
                // Ambil Before Balance (BB) dari Item Master
                $item   = $this->itemMasterRepository->getById($data['item_id']);
                $bbQty = $item->current_stock;

                //Switch Logic Doc_type
                switch ($data['doc_type']) {
                    case 'PORC':
                        $data['bb_qty']         = $bbQty;
                        $data['in_qty']         = $data['in_qty'];
                        $data['out_qty']        = 0;
                        $data['eb_qty']         = $bbQty + $data['in_qty'];
                        break;

                    case 'CONS':
                        $data['bb_qty']         = $bbQty;
                        $data['in_qty']         = 0;
                        $data['out_qty']        = $data['out_qty'];
                        $data['eb_qty']         = $bbQty - $data['out_qty'];
                        break;

                    case 'ADJI':
                        $data['bb_qty']         = $bbQty;
                        if ($data['adj_type']       === 'in') {
                            $data['in_qty']         = $data['in_qty'];
                            $data['out_qty']        = 0;
                            $data['eb_qty']         = $bbQty + $data['in_qty'];
                            $newStock               = $item->current_stock + $data['in_qty'];
                        } else {
                            $data['in_qty']         = 0;
                            $data['out_qty']        = $data['out_qty'];
                            $data['eb_qty']         = $bbQty - $data['out_qty'];
                            $newStock               = $item->current_stock - $data['out_qty'];
                        }
                        break;
                }

                // Simpan Data Transaksi
                $transaction = $this->transactionRepository->create($data);

                // Update Stock di Item Master
                $this->itemMasterRepository->update($data['item_id'], [
                    'current_stock' => $newStock
                ]);

                return $transaction;
            });
        } catch (\Exception $e) {
            // Log error atau lakukan penanganan kesalahan lainnya
            throw new \Exception("Gagal membuat transaksi: " . $e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        $data['status'] = 'UPDATED';
        return $this->transactionRepository->update($id, $data);
    }

    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $transaction    = $this->transactionRepository->getById($id);
                $item           = $this->itemMasterRepository->getById($transaction->item_id);

                $newStock = $item->current_stock;

                switch ($transaction->doc_type) {
                    case 'PORC':
                        $newStock = $item->current_stock - $transaction->in_qty;
                        break;
                    case 'CONS':
                        $newStock = $item->current_stock + $transaction->out_qty;
                        break;
                    case 'ADJI':
                        if ($transaction->adj_type === 'in') {
                            $newStock = $item->current_stock - $transaction->in_qty;
                        } else {
                            $newStock = $item->current_stock + $transaction->out_qty;
                        }
                        break;
                }
                $this->itemMasterRepository->update($transaction->item_id, [
                    'current_stock' => $newStock
                ]);

                return $this->transactionRepository->delete($id);
            });
        } catch (\Exception $e) {
            // Log error atau lakukan penanganan kesalahan lainnya
            throw new \Exception("Gagal menghapus transaksi: " . $e->getMessage());
        }
    }
}
