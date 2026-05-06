@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create User</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('users.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Nama</label>
                                <input type="text" class="form-control uppercase @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}">
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
                                    <option value="admin" {{ old('designation') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="manager" {{ old('designation') == 'manager' ? 'selected' : '' }}>Manager
                                    </option>
                                    <option value="staff" {{ old('designation') == 'staff' ? 'selected' : '' }}>Staff
                                    </option>
                                </select>
                                @error('designation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Organization Code</label>
                                <select class="form-control @error('orgn_code') is-invalid @enderror" name="orgn_code">
                                    <option value="" disabled selected>-- Select Orgn Code --</option>
                                    <option value="SFPL" {{ old('orgn_code') == 'SFPL' ? 'selected' : '' }}>SFPL</option>
                                    <option value="FY1" {{ old('orgn_code') == 'FY1' ? 'selected' : '' }}>FY1</option>
                                    <option value="FY2" {{ old('orgn_code') == 'FY2' ? 'selected' : '' }}>FY2</option>
                                    <option value="FY3" {{ old('orgn_code') == 'FY3' ? 'selected' : '' }}>FY3</option>
                                    <option value="P-BX" {{ old('orgn_code') == 'P-BX' ? 'selected' : '' }}>P-BX</option>
                                    <option value="P-CP" {{ old('orgn_code') == 'P-CP' ? 'selected' : '' }}>P-CP</option>
                                    <option value="IT" {{ old('orgn_code') == 'IT' ? 'selected' : '' }}>IT</option>
                                </select>
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
                                    <option value="Male" {{ old('gander') == 'Male' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Female" {{ old('gander') == 'Female' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('gander')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Nomor Telpon</label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                    name="mobile" value="{{ old('mobile') }}">
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
