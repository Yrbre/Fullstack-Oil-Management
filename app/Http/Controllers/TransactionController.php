<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdjustmentStockRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Services\Interfaces\ItemMasterServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    protected TransactionServiceInterface $transactionService;
    protected ItemMasterServiceInterface $itemMasterService;

    public function __construct(
        TransactionServiceInterface $transactionService,
        ItemMasterServiceInterface $itemMasterService
    ) {
        $this->transactionService = $transactionService;
        $this->itemMasterService = $itemMasterService;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $transactions = $this->transactionService->getAll();

                if ($request->date_from && $request->date_to) {
                    $transactions->whereBetween('trans_date', [
                        $request->date_from,
                        $request->date_to
                    ]);
                }

                return DataTables::of($transactions)
                    ->addIndexColumn()
                    ->addColumn('trans_date', fn($row) => Carbon::parse($row->trans_date)->format('d-M-Y'))
                    ->addColumn('trans_qty', function ($row) {
                        if ($row->doc_type == 'PORC') return number_format((float)$row->in_qty, 1, ',', '.');
                        if ($row->doc_type == 'CONS') return number_format((float)$row->out_qty, 1, ',', '.');
                        if ($row->doc_type == 'ADJI' && $row->in_qty > 0) return number_format((float)$row->in_qty, 1, ',', '.');
                        if ($row->doc_type == 'ADJI' && $row->out_qty > 0) return number_format((float)$row->out_qty, 1, ',', '.');
                        return 'N/a';
                    })
                    ->addColumn('eb_qty', fn($row) => number_format((float)$row->eb_qty, 1, ',', '.'))
                    ->rawColumns([])
                    ->make(true);
            }
            return view('pages.Transaction.index');
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
            $items = $this->itemMasterService->getByOrgnCode(auth()->user()->orgn_code);
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
            if ($request->input('redirect_to') === 'create') {
                return redirect()->route('transactions.create')->with('success', 'Transaksi berhasil disimpan.');
            }
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan transaksi.' . $e->getMessage())
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
            $transSameDate = $this->transactionService->getSameDateTransactions(
                $transaction->trans_date,
                $transaction->item_id
            );
            return view('pages.Transaction.show', compact('transaction', 'transSameDate'));
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
            $items = $this->itemMasterService->getByOrgnCode(auth()->user()->orgn_code);
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

    public function adjustmentStock()
    {
        try {
            $items = $this->itemMasterService->getByOrgnCode(auth()->user()->orgn_code);
            return view('pages.Transaction.adjustment_stock', compact('items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat halaman adjustment stock.');
        }
    }

    public function storeAdjustmentStock(StoreAdjustmentStockRequest $request)
    {
        try {
            $this->transactionService->create(
                $request->validated(),
                auth()->user()->name
            );
            return redirect()->route('transactions.index')->with('success', 'Adjustment stock berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan adjustment stock.' . $e->getMessage())
                ->withInput();
        }
    }
}
