@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-rectangle-list" style="color:#ffffff "></i> Item Inventory</h2>
                <a href="{{ route('item-locations.create') }}" class="btn btn-primary">Add Item Inventory</a>
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
                                        <th>Nama Item</th>
                                        <th>Lokasi - Tag</th>
                                        <th>Demander</th>
                                        <th>Q.tity</th>
                                        <th>Weight</th>
                                        <th>Unit</th>
                                        <th>Exp Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <form id="deleteItemLocationForm" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div> <!-- simple table -->
            </div> <!-- end section -->
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
    {{-- DataTableScript --}}
    @push('scripts')
        <script>
            $(document).on('click', '#deleteItemLocationBtn', function() {
                var itemLocationId = $(this).data('id');
                var itemLocationName = $(this).data('name');
                var itemLocationDepartment = $(this).data('department');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete the item '" + itemLocationName +
                        "' ?",
                    icon: 'warning',
                    theme: 'dark',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('#deleteItemLocationForm');
                        form.attr('action', '/item-locations/' + itemLocationId);
                        form.submit();
                    }
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                //simpan ke variable
                let table = $('#dataTable-1').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('item-locations.index') }}",
                        data: function(d) {
                            d.date_from = $('#filterDateFrom').val();
                            d.date_to = $('#filterDateTo').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'item_name',
                            name: 'item_name'
                        },
                        {
                            data: 'warehouse_name',
                            name: 'warehouse_name'
                        },
                        {
                            data: 'orgn_code',
                            name: 'orgn_code'
                        },
                        {
                            data: 'qty',
                            name: 'qty',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'weight',
                            name: 'weight',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'unit',
                            name: 'unit'
                        },
                        {
                            data: 'exp_date',
                            name: 'exp_date'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    lengthMenu: [
                        [16, 32, 64, -1],
                        [16, 32, 64, 'All']
                    ]
                });

                // btnFilter setelah table didefinisikan
                $('#btnFilter').on('click', function() {
                    table.draw();
                });
            });
        </script>
    @endpush
@endsection
