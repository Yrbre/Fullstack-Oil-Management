@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Edit User</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Nama</label>
                                <input type="text" class="form-control uppercase @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Designation</label>
                                <select class="form-control @error('designation') is-invalid @enderror" name="designation">
                                    <option value="">-- Select Designation --</option>
                                    <option value="manager"
                                        {{ old('designation', $user->designation) == 'manager' ? 'selected' : '' }}>Manager
                                    </option>
                                    <option value="staff"
                                        {{ old('designation', $user->designation) == 'staff' ? 'selected' : '' }}>Staff
                                    </option>
                                </select>
                                @error('designation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Organization Code</label>
                                <input type="text"
                                    class="form-control uppercase @error('orgn_code') is-invalid @enderror" name="orgn_code"
                                    value="{{ old('orgn_code', $user->orgn_code) }}">
                                @error('orgn_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Jenis Kelamin</label>
                                <select class="form-control @error('gander') is-invalid @enderror" name="gander">
                                    <option value="">-- Select gander --</option>
                                    <option value="Male" {{ old('gander', $user->gander) == 'Male' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="Female"
                                        {{ old('gander', $user->gander) == 'Female' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('gander')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Nomor Telpon</label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                    name="mobile" value="{{ old('mobile', $user->mobile) }}">
                                @error('mobile')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-danger mr-3">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
        </div> <!-- /. col -->
    </div> <!-- /. end-section -->
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
@endsection
