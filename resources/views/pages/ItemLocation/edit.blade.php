@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Item Information</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('item-locations.update', $itemLocation->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label id="select2-label" for="select2-item_name">Item Name</label>
                                <select class="form-control select2-item_name @error('item_id') is-invalid @enderror"
                                    name="item_id" id="select2-item_name">
                                    <option value="" disabled selected>-- Select Item --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('item_id', $itemLocation->item->id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->item_desc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label id="select2-label" for="select2-warehouse">Location</label>
                                <select class="form-control select2-warehouse @error('warehouse_id') is-invalid @enderror"
                                    name="warehouse_id" id="select2-warehouse">
                                    <option value="" disabled selected>-- Select Location --</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ old('warehouse_id', $itemLocation->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} - Tag {{ $warehouse->tag }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label id="select2-label" for="select2-demander">Demander</label>
                                <select class="form-control select2-demander @error('orgn_code') is-invalid @enderror"
                                    name="orgn_code" id="select2-demander">
                                    <option value="" disabled selected>-- Select Demander --</option>
                                    @foreach ($departments as $item)
                                        <option value="{{ $item->code }}"
                                            {{ old('orgn_code', $itemLocation->orgn_code) == $item->code ? 'selected' : '' }}>
                                            {{ $item->code }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('orgn_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Vendor Information</strong>
                </div>
                <div class ="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputAddress2">Vendor Lot</label>
                            <input type="text" class="form-control @error('vendor_lot') is-invalid @enderror"
                                name="vendor_lot" value="{{ old('vendor_lot', $itemLocation->vendor_lot) }}">
                            @error('vendor_lot')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputAddress2">Production Date</label>
                            <input type="month" class="form-control @error('production_date') is-invalid @enderror"
                                name="production_date"
                                value="{{ old('production_date', optional($itemLocation->production_date)->format('Y-m')) }}">
                            @error('production_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputAddress2">Product Package</label>
                            <input type="text" class="form-control @error('package') is-invalid @enderror" name="package"
                                value="{{ old('package', $itemLocation->package) }}">
                            @error('package')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCity">Total Package</label>
                            <input type="text" class="form-control @error('qty_unit') is-invalid @enderror"
                                name="qty_unit" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                value="{{ old('qty_unit', $itemLocation->qty_unit) }}">
                            @error('qty_unit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCity">Total Weight</label>
                            <input type="text" class="form-control @error('qty_weight') is-invalid @enderror"
                                name="qty_weight" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                value="{{ old('qty_weight', $itemLocation->qty_weight) }}">
                            @error('qty_weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputAddress2">Received Date</label>
                            <input type="date" class="form-control @error('received_date') is-invalid @enderror"
                                name="received_date"
                                value="{{ old('received_date', optional($itemLocation->received_date)->format('Y-m-d')) }}">
                            @error('received_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputAddress2">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $itemLocation->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
            </div>
            </form>
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
        <script>
            $('.select2-item_name').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 0,
                width: '100%',
            });
            $('.select2-warehouse').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 0,
                width: '100%',
            });
            $('.select2-demander').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 0,
                width: '100%',
            });
        </script>
    @endpush
@endsection
