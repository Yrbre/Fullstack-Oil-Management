<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemLocationRequest;
use App\Http\Requests\UpdateItemLocationRequest;
use App\Services\DepartmentService;
use App\Services\ItemLocationService;
use App\Services\ItemMasterService;
use App\Services\WarehouseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemLocationsController extends Controller
{
    protected ItemMasterService $itemMasterService;
    protected WarehouseService $warehouseService;
    protected DepartmentService $departmentService;
    protected ItemLocationService $itemLocationService;

    public function __construct(
        ItemMasterService $itemMasterService,
        WarehouseService $warehouseService,
        DepartmentService $departmentService,
        ItemLocationService $itemLocationService
    ) {
        $this->itemMasterService = $itemMasterService;
        $this->warehouseService = $warehouseService;
        $this->departmentService = $departmentService;
        $this->itemLocationService = $itemLocationService;
    }

    public function index(Request $request)
    {


        try {
            if ($request->ajax()) {
                $itemLocations = $this->itemLocationService->getAll();

                return DataTables::of($itemLocations)
                    ->addIndexColumn()
                    ->addColumn('exp_date', function ($row) {
                        return $row->exp_date ? Carbon::parse($row->exp_date)->format('M Y') : 'N/A';
                    })
                    ->addColumn('item_name', function ($row) {
                        return $row->item ? $row->item->item_desc : 'N/A';
                    })
                    ->addColumn('warehouse_name', function ($row) {
                        return $row->warehouse_id ? $row->warehouse->name . ' - ' . ' Tag ' . $row->warehouse->tag : 'N/A';
                    })
                    ->addColumn('qty', function ($row) {
                        return $row->qty_unit ? $row->qty_unit . ' ' . $row->package : 'N/A';
                    })
                    ->addColumn('weight', function ($row) {
                        return $row->qty_weight ? number_format($row->qty_weight, 0, ',', '.') : 'N/A';
                    })
                    ->addColumn('unit', function ($row) {
                        return $row->item->item_uom ? $row->item->item_uom : 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        $buttonsEdit = '<a href="' . route('item-locations.edit', $row->id) . '" class="dropdown-item">Edit</a>';
                        $buttonDelete = '<button class="dropdown-item" type="button" id="deleteItemLocationBtn"
                                                        data-id="' . $row->id . '"
                        data-name="' . e($row->item->item_desc ?? '') . '">
                        Delete
                    </button>';
                        $dropdown =  '<button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">'
                            . $buttonsEdit .
                            $buttonDelete .

                            '</div>';
                        return $dropdown;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('pages.ItemLocation.index');
        } catch (\Exception $e) {
            Log::error('Error fetching item locations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data lokasi item: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $items = $this->itemMasterService->getAll();
            $warehouses = $this->warehouseService->getAll();
            $departments = $this->departmentService->getAll();
            return view('pages.ItemLocation.create', compact('items', 'warehouses', 'departments'));
        } catch (\Exception $e) {
            Log::error('Error fetching data for create item location form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menampilkan form: ' . $e->getMessage());
        }
    }

    public function store(StoreItemLocationRequest $request)
    {
        try {
            $data = $request->validated();
            $item = $this->itemMasterService->getById($data['item_id']);
            $data['type'] = $item->item_glclass ?? 'N/A';
            $this->itemLocationService->create($data);
            Log::info('Item location created successfully: Item ID ' . $data['item_id'] . ' User: ' . auth()->user()->name);
            return redirect()->route('item-locations.index')->with('success', 'Item Inventory created successfully.');
        } catch (\Exception $e) {
            Log::error('Error storing item location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data lokasi item: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $itemLocation = $this->itemLocationService->getById($id);
            $items = $this->itemMasterService->getAll();
            $warehouses = $this->warehouseService->getAll();
            $departments = $this->departmentService->getAll();
            return view('pages.ItemLocation.edit', compact('itemLocation', 'items', 'warehouses', 'departments'));
        } catch (\Exception $e) {
            Log::error('Error fetching item location for edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menampilkan form edit: ' . $e->getMessage());
        }
    }

    public function update(UpdateItemLocationRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $item = $this->itemMasterService->getById($data['item_id']);
            $data['type'] = $item->item_glclass ?? 'N/A';
            $this->itemLocationService->update($id, $data);
            Log::info('Item location updated successfully: ID ' . $id . ' User: ' . auth()->user()->name);
            return redirect()->route('item-locations.index')->with('success', 'Item Inventory updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating item location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data lokasi item: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->itemLocationService->delete($id);
            Log::info('Item location deleted successfully: ID ' . $id . ' User: ' . auth()->user()->name);
            return redirect()->route('item-locations.index')->with('success', 'Item Inventory deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting item location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data lokasi item: ' . $e->getMessage());
        }
    }
}
