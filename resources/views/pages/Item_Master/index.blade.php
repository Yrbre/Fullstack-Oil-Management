@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa fa-droplet" style="color:#DBCF5C "></i> Item Oil Master</h2>
                <a href="{{ route('item-master.create') }}" class="btn btn-primary">Add Item</a>
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
                                        <th>Item ID</th>
                                        <th>Item No</th>
                                        <th>Item Desc</th>
                                        <th>Current Stock</th>
                                        <th>Item Uom</th>
                                        <th>GL Class</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->item_id }}</td>
                                            <td>{{ $item->item_no }}</td>
                                            <td>{{ $item->item_desc }}</td>
                                            <td>{{ $item->current_stock ?? 'N/A' }}</td>
                                            <td>{{ $item->item_uom }}</td>
                                            <td>{{ $item->item_glclass }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('item-master.edit', $item->id) }}">Edit</a>
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
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    theme: 'dark',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false,
                });
            @endif
        </script>
        <script>
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    theme: 'dark',
                    text: '{{ session('error') }}',
                    timer: 2000,
                    showConfirmButton: false,
                });
            @endif
        </script>
    @endpush
@endsection
