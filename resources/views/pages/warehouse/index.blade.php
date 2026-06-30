@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-warehouse text-primary"></i> Warehouses Master</h2>
                <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Add Warehouse</a>
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
                                        <th>Warehouse Name</th>
                                        <th>Tag</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $warehouse)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $warehouse->name }}</td>
                                            <td>{{ $warehouse->tag }}</td>
                                            <td>{{ $warehouse->department->name ?? 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('warehouses.edit', $warehouse->id) }}">Edit</a>
                                                    <button class="dropdown-item" type="button" id="deleteWarehouseBtn"
                                                        data-id="{{ $warehouse->id }}" data-name="{{ $warehouse->name }}"
                                                        data-department="{{ $warehouse->department->name ?? 'N/A' }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <form id="deleteWarehouseForm" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div> <!-- simple table -->
            </div> <!-- end section -->
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
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

        <script>
            $(document).on('click', '#deleteWarehouseBtn', function() {
                var warehouseId = $(this).data('id');
                var warehouseName = $(this).data('name');
                var warehouseDepartment = $(this).data('department');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete the warehouse '" + warehouseName +
                        "' from department '" + warehouseDepartment + "'?",
                    icon: 'warning',
                    theme: 'dark',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('#deleteWarehouseForm');
                        form.attr('action', '/warehouses/' + warehouseId);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
