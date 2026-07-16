<?php

namespace App\Http\Controllers;

use App\Models\ItemLocations;
use App\Services\ItemLocationService;
use App\Services\ItemMasterService;
use App\Services\TransferRequestService;
use App\Services\WarehouseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TransferRequestsController extends Controller
{
    protected TransferRequestService $transfer_request_service;
    protected ItemMasterService $item_master_service;
    protected WarehouseService $warehouse_service;
    protected ItemLocationService $item_location_service;
    public function __construct(
        TransferRequestService $transfer_request_service,
        ItemMasterService $item_master_service,
        WarehouseService $warehouse_service,
        ItemLocationService $item_location_service,
    ) {
        $this->transfer_request_service = $transfer_request_service;
        $this->item_master_service = $item_master_service;
        $this->warehouse_service = $warehouse_service;
        $this->item_location_service = $item_location_service;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $transfer_request_service = $this->transfer_request_service->getAll();

                if ($request->date_from && $request->date_to) {
                    $transfer_request_service->whereBetween('requested_date', [
                        $request->date_from,
                        $request->date_to
                    ]);
                }

                return DataTables::of($transfer_request_service)
                    ->addIndexColumn()
                    ->addColumn('item', fn($row) => $row->item->item_desc)
                    ->addColumn('source_warehouse', fn($row) => $row->source_warehouse->name . ' Tag-' . $row->source_warehouse->tag)
                    ->addColumn('destination_warehouse', fn($row) => $row->destination_warehouse->name . ' Tag-' . $row->destination_warehouse->tag)
                    ->addColumn('department', fn($row) => $row->department->code . ' - ' . $row->department->name)
                    ->addColumn('requester', fn($row) => $row->requester->name)
                    ->addColumn('request_date', fn($row) => Carbon::parse($row->requested_date)->format('d-M-Y'))
                    ->addColumn('request_qty', fn($row) => number_format((float)$row->requested_qty, 1, ',', '.'))
                    ->rawColumns(['item', 'source_warehouse', 'destination_warehouse', 'department', 'requester', 'request_date', 'request_qty'])
                    ->make(true);
            }
            return view('pages.transfer_requests.index');
        } catch (\Exception $e) {
            Log::error('Error fetching transfer requests: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()) . 'Data: ' . json_encode($request->all()));
            return redirect()->back()->with('error', 'Data Transfer Tidak Dapat Ditampilkan, Silahkan Hubungi IT Support');
        }
    }

    public function create()
    {
        try {
            if (auth()->user()->designation == 'admin') {
                $items = $this->item_master_service->getAll();
                $source_warehouses = $this->warehouse_service->getAll();
            } else {
                $items = $this->item_master_service->getByOrgnCode(auth()->user()->department_id);
                $source_warehouses = $this->warehouse_service->getByOrgnCode(auth()->user()->department_id);
            }
            return view('pages.transfer_requests.create', compact('items', 'source_warehouses'));
        } catch (\Exception $e) {
            Log::error('Error creating transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()));
            return redirect()->back()->with('error', 'Tidak Dapat Membuat Permintaan Transfer, Silahkan Hubungi IT Support');
        }
    }

    public function store(Request $request)
    {
        try {
            $this->transfer_request_service->create($request->all());
            return redirect()->route('transfer-requests.index')->with('success', 'Permintaan Transfer Berhasil Dibuat');
        } catch (\Exception $e) {
            Log::error('Error storing transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()) . 'Data: ' . json_encode($request->all()));
            return redirect()->back()->with('error', 'Tidak Dapat Menyimpan Permintaan Transfer, Silahkan Hubungi IT Support')->withInput();
        }
    }

    public function show(string $id)
    {
        try {
            $transfer_request = $this->transfer_request_service->getById($id);
            return view('pages.transfer_requests.show', compact('transfer_request'));
        } catch (\Exception $e) {
            Log::error('Error showing transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()));
            return redirect()->back()->with('error', 'Tidak Dapat Menampilkan Permintaan Transfer, Silahkan Hubungi IT Support');
        }
    }

    public function edit(string $id)
    {
        try {
            $transfer_request = $this->transfer_request_service->getById($id);
            return view('pages.transfer_requests.edit', compact('transfer_request'));
        } catch (\Exception $e) {
            Log::error('Error editing transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()));
            return redirect()->back()->with('error', 'Tidak Dapat Mengedit Permintaan Transfer, Silahkan Hubungi IT Support');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->transfer_request_service->update($id, $request->all());
            return redirect()->route('transfer-requests.index')->with('success', 'Permintaan Transfer Berhasil Diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()) . 'Data: ' . json_encode($request->all()));
            return redirect()->back()->with('error', 'Tidak Dapat Memperbarui Permintaan Transfer, Silahkan Hubungi IT Support')->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->transfer_request_service->delete($id);
            return redirect()->route('transfer-requests.index')->with('success', 'Permintaan Transfer Berhasil Dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting transfer request: ' . $e->getMessage() . 'User:' . auth()->user()->name . 'IP:' . request()->ip() . 'Hostname: ' . gethostbyaddr(request()->ip()));
            return redirect()->back()->with('error', 'Tidak Dapat Menghapus Permintaan Transfer, Silahkan Hubungi IT Support');
        }
    }

    public function getStock(Request $request)
    {
        $itemId = $request->item_id;
        $warehouseId = $request->warehouse_id;

        if (!$itemId || !$warehouseId) {
            return response()->json(['error' => 'Item ID and Warehouse ID are required'], 400);
        }

        $stock = ItemLocations::where('item_id', $itemId)->where('warehouse_id', $warehouseId)->sum('qty_weight');
        $item = $this->item_master_service->getById($itemId);

        return response()->json([
            'stock' => $stock,
            'uom' => $item->item_uom
        ]);
    }
}
