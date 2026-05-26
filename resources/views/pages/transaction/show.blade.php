@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <a class="h5 page-title text-success" href="{{ url()->previous() }}">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Transaksi
                        </a>
                        <h5 class="p-4">Detail Transaksi</h5>
                    </div>
                </div>
                <!-- widgets -->
                <div class="row my-4">
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Informasi Transaksi</h5>
                                <div class="row">
                                    <div class="col-4">
                                        <small class="text-muted">Tanggal Transaksi</small>
                                        <p>{{ \Carbon\Carbon::parse($transaction->trans_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">No Item</small>
                                        <p>{{ $transaction->item_no }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Nama Item</small>
                                        <p>{{ $transaction->item_desc }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Quantity Transaksi</small>
                                        <p>{{ number_format($transaction->trans_qty, 1, ',', '.') }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Stok Akhir</small>
                                        <p>{{ number_format($transaction->eb_qty, 1, ',', '.') }}</p>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Status</small>
                                        <p>
                                            @if ($transaction->adj_qty)
                                                <span class="badge badge-warning">ADJUSTMENT</span>
                                            @else
                                                <span class="badge badge-info">NORMAL</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Catatan</small>
                                        <p>{{ $transaction->catatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Detail Adjustment</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No Item</th>
                                                <th>Nama Item</th>
                                                <th>Quantity</th>
                                                <th>Doc Type</th>
                                                <th>Created By</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transSameDate as $trans)
                                                <tr>
                                                    <td>{{ $trans->item_no }}</td>
                                                    <td>{{ $trans->item_desc }} </td>
                                                    <td>{{ number_format($trans->trans_qty, 1, ',', '.') }}</td>
                                                    <td>{{ $trans->adj_type ? $trans->doc_type . ' - ' . $trans->adj_type : $trans->doc_type }}
                                                    </td>
                                                    <td>{{ $trans->created_by }}</td>
                                                    <td>{{ $trans->catatan ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada transaksi lain pada
                                                        tanggal ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div>
@endsection
