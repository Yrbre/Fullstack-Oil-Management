@extends('layouts.template')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-users text-info"></i> Users</h2>
                <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Organization Code</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->designation }}</td>
                                            <td>{{ $item->orgn_code }}</td>
                                            <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('users.edit', $item->id) }}">Edit</a>
                                                    <button class="dropdown-item"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                                        Nonaktifkan
                                                    </button>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('users.destroy', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
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
