@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <a class="h5 page-title text-success" href="{{ route('dashboard') }}"> <i
                                class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h5 class="p-4">Detail Item</h5>
                    </div>
                    <div class="col-auto">
                        <form method="GET" action="{{ route('item-master.detail', $item->id) }}" id="filterForm">
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">

                                <select name="month" class="form-control" style="width: 140px;"
                                    onchange="this.form.submit()">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="year" class="form-control" style="width: 100px;"
                                    onchange="this.form.submit()">
                                    @foreach (range(now()->year, 2020, -1) as $y)
                                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- widgets -->
                <div class="row my-4">
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-barcode" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">NO ITEM</small>
                                        <h3 class="card-title mb-0">{{ $item->item_no }}</h3>

                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-oil-can" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">TYPE ITEM</small>
                                        <h3 class="card-title mb-0">{{ $item->item_desc }}</h3>
                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-pen-ruler" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">SATUAN</small>
                                        <h3 class="card-title mb-0">{{ $item->item_uom }}</h3>
                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                </div> <!-- end section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body px-4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>Beg. Balance</th>
                                            <th>Received</th>
                                            <th>Consume</th>
                                            <th>End Stock</th>
                                            <th>Adjustment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $trans)
                                            <tr
                                                class="{{ $trans->in_qty == 0 && $trans->out_qty == 0 && $trans->adj_qty == 0 ? 'text-muted' : '' }}">
                                                <td>{{ \Carbon\Carbon::parse($trans->trans_date)->format('d F Y') }}</td>
                                                <td>{{ number_format($trans->bb_qty, 0, ',', '.') }}</td>
                                                <td>{{ $trans->in_qty > 0 ? number_format($trans->in_qty, 0, ',', '.') : '-' }}
                                                </td>
                                                <td>{{ $trans->out_qty > 0 ? number_format($trans->out_qty, 0, ',', '.') : '-' }}
                                                </td>
                                                <td>{{ number_format($trans->eb_qty, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($trans->adj_qty)
                                                        <span class="badge badge-warning">ADJUSTMENT</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Tidak ada transaksi untuk
                                                    periode ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($transactions->isNotEmpty())
                                        <tfoot>
                                            <tr class="font-weight-bold">
                                                <th>Total</th>
                                                <th>-</th>
                                                <th>{{ number_format($transactions->sum('in_qty'), 0, ',', '.') }}</th>
                                                <th>{{ number_format($transactions->sum('out_qty'), 0, ',', '.') }}</th>
                                                <th class="text-success">
                                                    {{ number_format($transactions->last()->eb_qty, 0, ',', '.') }}</th>
                                                <th>{{ number_format($transactions->sum('adj_qty'), 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->
                </div> <!-- .row -->
            </div>
        </div>
    </div>
@endsection
