<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\DashboardRequest;
use App\Models\IcTransInv;
use App\Services\DashboardService;
use App\Services\Interfaces\ItemMasterServiceInterface;

class DashbaordController extends Controller
{
    protected $dashboardService;
    protected $itemMasterService;

    public function __construct(DashboardService $dashboardService, ItemMasterServiceInterface $itemMasterService)
    {
        $this->dashboardService = $dashboardService;
        $this->itemMasterService = $itemMasterService;
    }

    protected function prepareForValidation(): void
    {
        $latest = IcTransInv::selectRaw('bln, thn')
            ->orderBy('thn', 'desc')
            ->orderBy('bln', 'desc')
            ->first();

        $this->merge([
            'month' => $this->month ?? ($latest ? (int) $latest->bln : now()->month),
            'year'  => $this->year  ?? ($latest ? (int) $latest->thn : now()->year),
        ]);
    }

    public function index(DashboardRequest $request)
    {
        $month = $request->month;
        $year = $request->year;
        $itemMaster = $this->itemMasterService->getAll();

        return view('pages.dashboard', [
            'summary' => $this->dashboardService->getSummary($month, $year),
            'total_item' => $this->dashboardService->getTotalItem(),
            'total_consumption' => $this->dashboardService->getTotalConsumption($month, $year),
            'items'     => $this->dashboardService->getItemsWithConsumption($month, $year),
            'total_receipt' => $this->dashboardService->getTotalReceipt($month, $year),
            'itemMaster' => $itemMaster,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
