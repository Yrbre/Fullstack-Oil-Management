@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-building text-primary"></i> Departments Master</h2>
                <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
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
                                        <th>Department Name</th>
                                        <th>Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->code }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('departments.edit', $department->id) }}">Edit</a>
                                                    <button class="dropdown-item" type="button" id="deleteDepartmentBtn"
                                                        data-id="{{ $department->id }}" data-name="{{ $department->name }}"
                                                        data-code="{{ $department->code }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <form id="deleteDepartmentForm" method="POST" style="display: none;">
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
            $(document).on('click', '#deleteDepartmentBtn', function() {
                var departmentId = $(this).data('id');
                var departmentName = $(this).data('name');
                var departmentCode = $(this).data('code');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete the department '" + departmentName +
                        "' with code '" + departmentCode + "'?",
                    icon: 'warning',
                    theme: 'dark',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('#deleteDepartmentForm');
                        form.attr('action', '/departments/' + departmentId);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
