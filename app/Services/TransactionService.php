<?php

namespace App\Services;

use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{
    protected TransactionRepositoryInterface $transactionRepository;
    protected ItemMasterRepositoryInterface $itemMasterRepository;

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

    public function getById(int $id)
    {
        return $this->transactionRepository->getById($id);
    }

    public function getByItemId(string $itemId)
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

                // ✅ Ambil bb_qty dari eb_qty transaksi sebelumnya
                $prevTransaction = DB::table('ic_trans_inv')
                    ->where('item_id', $data['item_id'])
                    ->where('trans_date', '<=', $transDate->toDateString())
                    ->orderBy('trans_date', 'desc')
                    ->orderBy('creation_date', 'desc')
                    ->first();

                $bbQty    = $prevTransaction ? $prevTransaction->eb_qty : $item->current_stock;
                $newStock = $bbQty;

                switch ($data['doc_type']) {
                    case 'PORC':
                        $data['bb_qty']  = $bbQty;
                        $data['in_qty']  = $data['trans_qty'];
                        $data['out_qty'] = 0;
                        $data['eb_qty']  = $bbQty + $data['in_qty'];
                        $newStock        = $bbQty + $data['in_qty'];
                        break;

                    case 'CONS':
                        $data['bb_qty']  = $bbQty;
                        $data['in_qty']  = 0;
                        $data['out_qty'] = $data['trans_qty'];
                        $data['eb_qty']  = $bbQty - $data['out_qty'];
                        $newStock        = $bbQty - $data['out_qty'];
                        break;

                    case 'ADJI':
                        $data['bb_qty'] = $bbQty;
                        if ($data['adj_type'] === 'CONS') {
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

                // ✅ Simulasi dulu sebelum simpan jika ada transaksi setelahnya
                $hasNewerTransactions = DB::table('ic_trans_inv')
                    ->where('item_id', $data['item_id'])
                    ->where('trans_date', '>', $transDate->toDateString())
                    ->exists();

                if ($hasNewerTransactions) {
                    $this->simulateRecalculate(
                        $data['item_id'],
                        $transDate,
                        $data['trans_qty'],
                        $data['doc_type'],
                        $data['adj_type'] ?? null
                    );
                }

                $transaction = $this->transactionRepository->create($data);

                if ($hasNewerTransactions) {
                    // Back date → recalculate semua dari tanggal ini
                    $this->recalculateStockFrom($data['item_id'], $transDate);
                } else {
                    // Normal → update current_stock biasa
                    $this->itemMasterRepository->update($data['item_id'], [
                        'current_stock' => $newStock,
                    ]);
                }

                return $transaction;
            });
        } catch (\Exception $e) {
            throw new \Exception("Gagal membuat transaksi: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data, string $updatedBy)
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

    public function delete(int $id)
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

    private function recalculateStockFrom(string $itemId, Carbon $fromDate): void
    {
        $prevTransaction = DB::table('ic_trans_inv')
            ->where('item_id', $itemId)
            ->where('trans_date', '<', $fromDate->toDateString())
            ->orderBy('trans_date', 'desc')
            ->orderBy('creation_date', 'desc')
            ->first();

        $runningBalance = $prevTransaction ? $prevTransaction->eb_qty : 0;

        $transactions = DB::table('ic_trans_inv')
            ->where('item_id', $itemId)
            ->where('trans_date', '>=', $fromDate->toDateString())
            ->orderBy('trans_date', 'asc')
            ->orderBy('creation_date', 'asc')
            ->get();

        foreach ($transactions as $trx) {
            $bbQty = $runningBalance;
            $ebQty = $bbQty + $trx->in_qty - $trx->out_qty;

            // ✅ Validasi eb_qty tidak boleh minus
            if ($ebQty < 0) {
                throw new \Exception(
                    "Stock tidak mencukupi pada tanggal {$trx->trans_date}. " .
                        "Stock tersedia: " . number_format($bbQty, 0, ',', '.') . ", " .
                        "Dibutuhkan: " . number_format($trx->out_qty, 0, ',', '.')
                );
            }

            DB::table('ic_trans_inv')
                ->where('id', $trx->id)
                ->update([
                    'bb_qty' => $bbQty,
                    'eb_qty' => $ebQty,
                ]);

            $runningBalance = $ebQty;
        }

        $this->itemMasterRepository->update($itemId, [
            'current_stock' => $runningBalance,
        ]);
    }

    private function simulateRecalculate(string $itemId, Carbon $fromDate, float $transQty, string $docType, ?string $adjType): void
    {
        // ✅ Ambil eb_qty dari transaksi sebelum fromDate
        $prevTransaction = DB::table('ic_trans_inv')
            ->where('item_id', $itemId)
            ->where('trans_date', '<', $fromDate->toDateString())
            ->orderBy('trans_date', 'desc')
            ->orderBy('creation_date', 'desc')
            ->first();

        // ✅ Jika tidak ada transaksi sebelumnya, ambil dari item master
        $item = $this->itemMasterRepository->getById($itemId);
        $runningBalance = $prevTransaction ? $prevTransaction->eb_qty : $item->current_stock;

        // Hitung balance setelah transaksi baru
        if ($docType === 'CONS') {
            $runningBalance -= $transQty;
        } elseif ($docType === 'PORC') {
            $runningBalance += $transQty;
        } elseif ($docType === 'ADJI') {
            $runningBalance += $adjType === 'CONS' ? $transQty : -$transQty;
        }

        if ($runningBalance < 0) {
            throw new \Exception(
                "Stock tidak mencukupi pada tanggal {$fromDate->toDateString()}. " .
                    "Stock tersedia: " . number_format($prevTransaction?->eb_qty ?? $item->current_stock, 0, ',', '.') . ", " .
                    "Dibutuhkan: " . number_format($transQty, 0, ',', '.')
            );
        }

        $transactions = DB::table('ic_trans_inv')
            ->where('item_id', $itemId)
            ->where('trans_date', '>=', $fromDate->toDateString())
            ->orderBy('trans_date', 'asc')
            ->orderBy('creation_date', 'asc')
            ->get();

        foreach ($transactions as $trx) {
            $ebQty = $runningBalance + $trx->in_qty - $trx->out_qty;

            if ($ebQty < 0) {
                throw new \Exception(
                    "Stock tidak mencukupi pada tanggal {$trx->trans_date}. " .
                        "Jika back date ini disimpan, stock pada tanggal tersebut akan menjadi " .
                        number_format($ebQty, 0, ',', '.') . ". " .
                        "Harap sesuaikan quantity."
                );
            }

            $runningBalance = $ebQty;
        }
    }
}
