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
        return IcItemMst::where('deleted_at', null)->count();
    }

    public function getTotalConsumption(int $month, int $year)
    {
        return IcTransInv::where('bln', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->where('thn', (string) $year)
            ->sum('out_qty');
    }

    public function getTotalReceipt(int $month, int $year)
    {
        return IcTransInv::where('bln', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->where('thn', (string) $year)
            ->sum('in_qty');
    }

    public function getItemsWithConsumption(int $month, int $year): Collection
    {
        $bln = str_pad($month, 2, '0', STR_PAD_LEFT);
        $thn = (string) $year;

        return IcItemMst::where('inactive_ind', 0)
            ->withSum(['transaction as total_consumption' => function ($q) use ($bln, $thn) {
                $q->where('bln', $bln)->where('thn', $thn);
            }], 'out_qty')
            ->withSum(['transaction as total_receipt' => function ($q) use ($bln, $thn) {
                $q->where('bln', $bln)->where('thn', $thn);
            }], 'in_qty')
            ->orderBy('item_no')
            ->get();
    }
}
