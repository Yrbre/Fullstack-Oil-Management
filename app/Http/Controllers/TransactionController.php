<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Services\Interfaces\ItemMasterServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;

class TransactionController extends Controller
{
    protected $transactionService;
    protected $itemMasterService;

    public function __construct(
        TransactionServiceInterface $transactionService,
        ItemMasterServiceInterface $itemMasterService
    ) {
        $this->transactionService = $transactionService;
        $this->itemMasterService = $itemMasterService;
    }
    public function index()
    {
        try {
            $transactions = $this->transactionService->getall();
            return view('pages.Transaction.index', compact('transactions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data transaksi.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $items = $this->itemMasterService->getAll();
            return view('pages.Transaction.create', compact('items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            $this->transactionService->create(
                $request->validated(),
                auth()->user()->name
            );
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan transaksi.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = $this->transactionService->getById($id);
            return view('pages.Transaction.show', compact('transaction'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data transaksi.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $transaction = $this->transactionService->getById($id);
            $items = $this->itemMasterService->getAll();
            return view('pages.Transaction.edit', compact('transaction', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data transaksi.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, string $id)
    {
        try {
            $this->transactionService->update($id, $request->validated(), auth()->user()->name);
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui transaksi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->transactionService->delete($id);
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi.');
        }
    }
}
