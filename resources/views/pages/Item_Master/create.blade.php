@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create Item Oil</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('item-master.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Item ID</label>
                                <input type="text" class="form-control @error('item_id') is-invalid @enderror"
                                    name="item_id" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                    value="{{ old('item_id') }}">
                                @error('item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Item No</label>
                                <input type="text" class="form-control @error('item_no') is-invalid @enderror"
                                    name="item_no" value="{{ old('item_no') }}">
                                @error('item_no')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputAddress">Item Description</label>
                                <input type="text" class="form-control @error('item_desc') is-invalid @enderror"
                                    name="item_desc" value="{{ old('item_desc') }}">
                                @error('item_desc')
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
                        <div class="form-group">
                            <label for="inputAddress2">GL Class</label>
                            <input type="text" class="form-control @error('item_glclass') is-invalid @enderror"
                                name="item_glclass" value="{{ old('item_glclass') }}">
                            @error('item_glclass')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCity">Stock</label>
                                <input type="text" class="form-control @error('current_stock') is-invalid @enderror"
                                    name="current_stock" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                    value="{{ old('current_stock') }}">
                                @error('current_stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">UOM</label>
                                <input type="text" class="form-control @error('item_uom') is-invalid @enderror"
                                    name="item_uom" value="{{ old('item_uom') }}">
                                @error('item_uom')
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
