@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-file-invoice" style="color:#a3a3a3 "></i> Transaksi</h2>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">Input Data</a>
            </div>
            <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <!-- table -->
                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Item Name</th>
                                        <th>Doc</th>
                                        <th>Quantity Transaksi</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->trans_date)->format('d-M-Y') }}</td>
                                            <td>{{ $item->item_desc }}</td>
                                            <td>{{ $item->doc_type }}</td>
                                            @if ($item->doc_type == 'PORC')
                                                <td>{{ $item->in_qty }}</td>
                                            @elseif ($item->doc_type == 'CONS')
                                                <td>{{ $item->out_qty }}</td>
                                            @elseif ($item->doc_type == 'ADJI' && $item->in_qty > 0)
                                                <td>{{ $item->in_qty }}</td>
                                            @elseif ($item->doc_type == 'ADJI' && $item->out_qty > 0)
                                                <td>{{ $item->out_qty }}</td>
                                            @else
                                                <td>N/a</td>
                                            @endif
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->catatan }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('transactions.edit', $item->id) }}">Edit</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- simple table -->
            </div> <!-- end section -->
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
    {{-- DataTableScript --}}
    @push('scripts')
        <script>
            $('#dataTable-1').DataTable({
                autoWidth: true,
                lengthMenu: [
                    [16, 32, 64, -1],
                    [16, 32, 64, 'All']
                ]
            });
        </script>
    @endpush
@endsection
