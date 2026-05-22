<?php

namespace App\Services;

use App\Models\IcItemMst;
use App\Models\IcTransInv;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function getSummary(int $month, int $year)
    {
        return [
            'total_item' => $this->getTotalItem(),
            'total_consumption' => $this->getTotalConsumption($month, $year),
            'total_receipt' => $this->getTotalReceipt($month, $year),
        ];
    }

    public function getTotalItem()
    {
        return IcItemMst::where('deleted_at', null)->when(auth()->user()->orgn_code !== 'IT', function ($q) {
            $q->where('orgn_code', auth()->user()->orgn_code);
        })->count();
    }

    public function getTotalConsumption(int $month, int $year)
    {
        return IcTransInv::where('bln', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->where('thn', (string) $year)
            ->when(auth()->user()->orgn_code !== 'IT', function ($q) {
                $q->where('orgn_code', auth()->user()->orgn_code);
            })
            ->where('doc_type', '!=', 'ADJ') // exclude ADJ for consumption
            ->sum('out_qty');
    }

    public function getTotalReceipt(int $month, int $year)
    {
        return IcTransInv::where('bln', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->where('thn', (string) $year)
            ->when(auth()->user()->orgn_code !== 'IT', function ($q) {
                $q->where('orgn_code', auth()->user()->orgn_code);
            })
            ->where('doc_type', '!=', 'ADJ') // exclude ADJ for receipt
            ->sum('in_qty');
    }

    public function getItemsWithConsumption(int $month, int $year): Collection
    {
        $bln = str_pad($month, 2, '0', STR_PAD_LEFT);
        $thn = (string) $year;

        $sumConsumption = IcTransInv::selectRaw('item_no, SUM(out_qty) as total_consumption, SUM(in_qty) as total_receipt')
            ->where('bln', $bln)
            ->where('thn', $thn)
            ->when(auth()->user()->orgn_code !== 'IT', function ($q) {
                $q->where('orgn_code', auth()->user()->orgn_code);
            })
            ->groupBy('item_no');

        return IcItemMst::where('inactive_ind', 0)
            ->when(auth()->user()->orgn_code !== 'IT', function ($q) {
                $q->where('orgn_code', auth()->user()->orgn_code);
            })
            ->leftJoinSub($sumConsumption, 'consumption', function ($join) {
                $join->on('ic_item_mst.item_no', '=', 'consumption.item_no');
            })
            ->select(
                'ic_item_mst.*',
                'consumption.total_consumption',
                'consumption.total_receipt'
            )
            ->orderByDesc('consumption.total_consumption') // ✅ sort tertinggi
            ->get();
    }

    public function getTop10Consumption(int $month, int $year)
    {
        $bln = str_pad($month, 2, '0', STR_PAD_LEFT);
        $thn = (string) $year;

        $data = IcTransInv::selectRaw('item_no, item_desc, item_uom, SUM(out_qty) as total_consumption')
            ->where('bln', $bln)
            ->where('thn', $thn)
            ->where('out_qty', '>', 0)
            ->when(auth()->user()->orgn_code !== 'IT', function ($q) {
                $q->where('orgn_code', auth()->user()->orgn_code);
            })
            ->groupBy('item_no', 'item_desc', 'item_uom')
            ->orderByDesc('total_consumption')
            ->limit(10)
            ->get();

        // ✅ Padding sampai 10 data
        $padded = $data->toArray();
        while (count($padded) < 10) {
            $padded[] = [
                'item_no'           => '-',
                'item_desc'         => '-',
                'item_uom'          => '-',
                'total_consumption' => 0,
            ];
        }

        return collect($padded)->map(fn($item) => (object) $item);
    }
}
