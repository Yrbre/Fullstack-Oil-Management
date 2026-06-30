<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Departments;
use App\Services\WarehouseService;
use Illuminate\Support\Facades\Log;

class WarehousesController extends Controller
{
    protected WarehouseService $warehouseService;
    // protected DepartmentService $departmentService;

    public function __construct(
        WarehouseService $warehouseService,
        // DepartmentService $departmentService
    ) {
        $this->warehouseService = $warehouseService;
        // $this->departmentService = $departmentService;
    }

    public function index()
    {
        try {
            $warehouses = $this->warehouseService->getAll();
            return view('pages.warehouse.index', compact('warehouses'));
        } catch (\Exception $e) {
            Log::error('Error fetching warehouses: ' . $e->getMessage() . 'User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Memuat Data Gudang');
        }
    }


    public function create()
    {
        try {
            $departments = Departments::orderBy('name')->get();

            return view('pages.warehouse.create', compact('departments'));
        } catch (\Exception $e) {
            Log::error('Error loading create warehouse view: ' . $e->getMessage() . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Memuat Tampilan Create Gudang');
        }
    }

    public function store(StoreWarehouseRequest $request)
    {
        try {
            $this->warehouseService->create($request->validated());
            Log::info('Warehouse created successfully: ' . json_encode($request->validated()) . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->route('warehouses.index')->with('success', 'Gudang Berhasil Ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error storing warehouse: ' . $e->getMessage() . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Menambahkan Gudang');
        }
    }

    public function edit(string $id)
    {
        try {
            $warehouse = $this->warehouseService->getById($id);
            $departments = Departments::orderBy('name')->get();
            return view('pages.warehouse.edit', compact('warehouse', 'departments'));
        } catch (\Exception $e) {
            Log::error('Error loading edit warehouse view: ' . $e->getMessage() . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Memuat Tampilan Edit Gudang');
        }
    }

    public function update(UpdateWarehouseRequest $request, string $id)
    {
        try {
            $this->warehouseService->update($id, $request->validated());
            Log::info('Warehouse updated successfully: ' . json_encode($request->validated()) . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->route('warehouses.index')->with('success', 'Gudang Berhasil Diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating warehouse: ' . $e->getMessage() . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Memperbarui Gudang');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->warehouseService->delete($id);
            Log::info('Warehouse deleted successfully: ' . $id . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->route('warehouses.index')->with('success', 'Gudang Berhasil Dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting warehouse: ' . $e->getMessage() . ' User: ' . auth()->user()->name . 'IP Address: ' . request()->ip() . 'User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Gagal Menghapus Gudang');
        }
    }
}
