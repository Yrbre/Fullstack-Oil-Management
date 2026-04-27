<?php

namespace App\Services;

use App\Models\IcItemMst;
use App\Models\IcTransInv;

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
        return IcTransInv::where('bln', $month)->where('thn', $year)->sum('out_qty');
    }

    public function getTotalReceipt(int $month, int $year)
    {
        return IcTransInv::where('bln', $month)->where('thn', $year)->sum('in_qty');
    }
}
