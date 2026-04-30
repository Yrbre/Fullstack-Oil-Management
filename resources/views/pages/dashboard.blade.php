@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Header + Filter --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 font-weight-bold">Dashboard Finish Oil</h4>


                    <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">

                            <select name="month" class="form-control" style="width: 140px;" onchange="this.form.submit()">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="year" class="form-control" style="width: 100px;" onchange="this.form.submit()">
                                @foreach (range(now()->year, 2020, -1) as $y)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>

                        </div>
                    </form>

                </div>

                <div class="row my-4">
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-oil-well text-warning"></i> Total
                                            Item</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0" id="kpiTotalItem">{{ $summary['total_item'] }} Item</h3>
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
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-fire text-danger"></i> Total
                                            Consumption</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0" id="kpiTotalConsumption">
                                            {{ number_format($summary['total_consumption'], 0, ',', '.') }}
                                        </h3>
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
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-truck text-info"></i> Total
                                            Received</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0" id="kpiTotalReceipt">
                                            {{ number_format($summary['total_receipt'], 0, ',', '.') }}
                                        </h3>
                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                </div> <!-- end section -->
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NO ITEM</th>
                                            <th>ITEM NAME</th>
                                            <th>END STOCK</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->item_no }}</td>
                                                <td>{{ $item->item_desc }}</td>
                                                <td>{{ number_format($item->current_stock, 0, ',', '.') }}
                                                    {{ $item->item_uom }}</td>
                                                <td>
                                                    <a href="{{ route('item-master.detail', $item->id) }}"
                                                        class="btn btn-sm btn-primary">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->

                </div> <!-- .row -->
            </div> <!-- /.col -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->

    @push('scripts')
        <script>
            $('#dataTable-1').DataTable({
                autoWidth: true,
                lengthMenu: [
                    [10, 20, 30, -1],
                    [10, 20, 30, 'All']
                ]
            });
        </script>
    @endpush
@endsection
