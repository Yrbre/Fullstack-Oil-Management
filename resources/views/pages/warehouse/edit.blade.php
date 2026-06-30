@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Edit Warehouse</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('warehouses.update', $warehouse->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Warehouse Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $warehouse->name) }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Tag Location</label>
                                <input type="text" class="form-control @error('tag') is-invalid @enderror" name="tag"
                                    value="{{ old('tag', $warehouse->tag) }}">
                                @error('tag')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputAddress2" id="select2">Department</label>
                                <select class="form-control select2 @error('department_id') is-invalid @enderror"
                                    name="department_id" id="select2">
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id', $warehouse->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->code }} - {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                    </form>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
        </div> <!-- /. col -->
    </div> <!-- /. end-section -->
    @push('scripts')
        <script>
            $('.select2').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 0,
                width: '100%',
            });
        </script>

        <script>
            document.getElementById('myForm').addEventListener('submit', function(e) {
                const btn = document.getElementById('submitBtn');

                if (btn.disabled) {
                    e.preventDefault(); // cegah submit kedua
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Loading...';
            });
        </script>
    @endpush
@endsection
