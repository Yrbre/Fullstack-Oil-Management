<?php

namespace App\Services;

use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Carbon\Carbon;
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

    public function create(array $data, string $createdBy)
    {
        try {
            return DB::transaction(function () use ($data, $createdBy) {
                $item = $this->itemMasterRepository->getById($data['item_id']);


                $data['creation_date'] = now();
                $data['created_by']    = $createdBy;
                $data['status']        = 'NEW';

                $transDate       = Carbon::parse($data['trans_date']);
                $data['tgl']     = $transDate->format('d');
                $data['bln']     = $transDate->format('m');
                $data['thn']     = $transDate->format('Y');
                $data['periode'] = $transDate->format('M Y');

                $data['item_no']   = $item->item_no;
                $data['item_desc'] = $item->item_desc;
                $data['item_uom']  = $item->item_uom;

                $bbQty    = $item->current_stock;
                $newStock = $bbQty; // ✅ fix bug 2 - inisialisasi default

                switch ($data['doc_type']) {
                    case 'PORC':
                        $data['bb_qty']  = $bbQty;
                        $data['in_qty']  = $data['trans_qty']; // ✅ pakai trans_qty dari form
                        $data['out_qty'] = 0;
                        $data['eb_qty']  = $bbQty + $data['in_qty'];
                        $newStock        = $bbQty + $data['in_qty']; // ✅ fix bug 2
                        break;

                    case 'CONS':
                        $data['bb_qty']  = $bbQty;
                        $data['in_qty']  = 0;
                        $data['out_qty'] = $data['trans_qty']; // ✅ pakai trans_qty dari form
                        $data['eb_qty']  = $bbQty - $data['out_qty'];
                        $newStock        = $bbQty - $data['out_qty']; // ✅ fix bug 2
                        break;

                    case 'ADJI':
                        $data['bb_qty'] = $bbQty;
                        if ($data['adj_type'] === 'IN') { // ✅ konsisten uppercase
                            $data['in_qty']  = $data['trans_qty'];
                            $data['out_qty'] = 0;
                            $data['eb_qty']  = $bbQty + $data['in_qty'];
                            $newStock        = $bbQty + $data['in_qty'];
                        } else {
                            $data['in_qty']  = 0;
                            $data['out_qty'] = $data['trans_qty'];
                            $data['eb_qty']  = $bbQty - $data['out_qty'];
                            $newStock        = $bbQty - $data['out_qty'];
                        }
                        break;
                }

                $transaction = $this->transactionRepository->create($data);

                $this->itemMasterRepository->update($data['item_id'], [
                    'current_stock' => $newStock
                ]);

                return $transaction;
            });
        } catch (\Exception $e) {
            throw new \Exception("Gagal membuat transaksi: " . $e->getMessage());
        }
    }

    public function update($id, array $data, string $updatedBy)
    {
        try {
            return DB::transaction(function () use ($id, $data, $updatedBy) {
                // Data otomatis saat update
                $data['update_date'] = now();
                $data['update_by']   = $updatedBy;
                $data['status']      = 'UPDATED';

                // Jika trans_date diubah, pecah ulang
                if (isset($data['trans_date'])) {
                    $transDate         = Carbon::parse($data['trans_date']);
                    $data['tgl']       = $transDate->format('d');
                    $data['bln']       = $transDate->format('m');
                    $data['thn']       = $transDate->format('Y');
                    $data['periode']   = $transDate->format('M Y');
                }

                return $this->transactionRepository->update($id, $data);
            });
        } catch (\Exception $e) {
            throw new \Exception("Gagal mengupdate transaksi: " . $e->getMessage());
        }
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
