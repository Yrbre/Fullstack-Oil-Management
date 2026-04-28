<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Services\Interfaces\ItemMasterServiceInterface;
use Illuminate\Http\Request;

class ItemMasterController extends Controller
{

    protected $itemMasterService;

    public function __construct(ItemMasterServiceInterface $itemMasterService)
    {
        $this->itemMasterService = $itemMasterService;
    }


    public function index()
    {
        try {
            $items = $this->itemMasterService->getAll();
            return view('pages.Item_Master.index', compact('items'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while fetching items: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.Item_Master.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        try {
            $this->itemMasterService->create($request->validated());
            return redirect()->route('item-master.index')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while creating the item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $item = $this->itemMasterService->getById($id);
            return view('pages.Item_Master.show', compact('item'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while fetching the item: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $item = $this->itemMasterService->getById($id);
            return view('pages.Item_Master.edit', compact('item'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while fetching the item: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, string $id)
    {
        try {
            $this->itemMasterService->update($id, $request->validated());
            return redirect()->route('item-master.index')->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating the item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->itemMasterService->delete($id);
            return redirect()->route('item-master.index')->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while deleting the item: ' . $e->getMessage());
        }
    }

    public function detail($id, Request $request)
    {
        try {
            $month = $request->get('month', now()->month);
            $year  = $request->get('year', now()->year);

            $item         = $this->itemMasterService->getById($id);
            $transactions = $this->itemMasterService->getTransactionByMonth($id, $month, $year);

            return view('pages.Item_Master.detail', compact('item', 'transactions', 'month', 'year'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while fetching the item details: ' . $e->getMessage());
        }
    }
}
