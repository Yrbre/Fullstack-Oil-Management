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

        $transactions = IcTransInv::where('item_id', $id)
            ->where('bln', $bln)
            ->where('thn', $thn)
            ->orderBy('tgl', 'asc')
            ->get()
            ->groupBy(function ($item) {
                // Pakai tgl, bln, thn langsung — hindari timezone issue
                return $item->thn . '-' . $item->bln . '-' . $item->tgl;
            })
            ->map(function ($group) {

                $consGroup = $group->where('doc_type', 'CONS');
                $porcGroup = $group->where('doc_type', 'PORC');
                $adjiGroup = $group->where('doc_type', 'ADJI');

                $adjiCons  = $adjiGroup->where('adj_type', 'CONS');
                $adjiPorc  = $adjiGroup->where('adj_type', 'PORC');

                // CONSUME = total CONS out_qty - total ADJI CONS in_qty
                $consume = $consGroup->sum('out_qty') - $adjiCons->sum('in_qty');

                // RECEIVE = total PORC in_qty - total ADJI PORC out_qty
                $receive = $porcGroup->sum('in_qty') - $adjiPorc->sum('out_qty');

                // ADJ = tetap tampil nilai asli ADJI (positif jika PORC, negatif jika CONS)
                $adjQty = $adjiPorc->sum('in_qty') - $adjiCons->sum('in_qty');

                $dateKey = $group->first()->thn . '-' . $group->first()->bln . '-' . $group->first()->tgl;

                return (object) [
                    'trans_date' => $dateKey,
                    'bb_qty'     => $group->first()->bb_qty,
                    'in_qty'     => $receive,
                    'out_qty'    => $consume,
                    'adj_qty'    => $adjQty,
                    'eb_qty'     => $group->last()->eb_qty,
                    'doc_type'   => $group->pluck('doc_type')->unique()->implode(', '),
                ];
            });

        // Generate semua tanggal dalam bulan
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate   = $startDate->copy()->endOfMonth();
        $allDates  = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->format('Y-m-d'); // → "2026-03-01"

            if ($transactions->has($key)) {
                $allDates[] = $transactions[$key];
            } else {
                $allDates[] = (object) [
                    'trans_date' => $key,
                    'bb_qty'     => 0,
                    'in_qty'     => 0,
                    'out_qty'    => 0,
                    'adj_qty'    => 0,
                    'eb_qty'     => 0,
                    'doc_type'   => null,
                ];
            }
        }

        return collect($allDates);
    }
}
