@extends('layouts.template')
@section('content')
    @push('style')
        <style>
            .card-body {
                padding: 1rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table.dataTable {
                min-width: 800px;
                /* ✅ paksa lebar minimum tabel */
            }
        </style>
    @endpush
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
                        <div class="card-body" data-simplebar>
                            <!-- table -->
                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Item Name</th>
                                        <th>Doc</th>
                                        <th>Quantity Transaksi</th>
                                        <th>Ending Stock</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{{ route('transactions.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'trans_date',
                        name: 'trans_date'
                    },
                    {
                        data: 'item_desc',
                        name: 'item_desc'
                    },
                    {
                        data: 'doc_type',
                        name: 'doc_type'
                    },
                    {
                        data: 'trans_qty',
                        name: 'trans_qty',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'eb_qty',
                        name: 'eb_qty'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    },
                ],
                lengthMenu: [
                    [16, 32, 64, -1],
                    [16, 32, 64, 'All']
                ]
            });
        </script>
    @endpush
@endsection
