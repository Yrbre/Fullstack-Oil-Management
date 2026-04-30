@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create Transaction</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="form-row">
                            {{-- <div class="form-group col-md-4"> --}}
                            {{-- <label for="">Orgn Code</label> --}}
                            <input type="text" class="form-control @error('orgn_code') is-invalid @enderror"
                                name="orgn_code" value="{{ auth()->user()->orgn_code }}" hidden>
                            @error('orgn_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            {{-- </div> --}}
                            <div class="form-group col-md-1">
                                <label for="">Trans Date</label>
                                <input type="date" class="form-control @error('trans_date') is-invalid @enderror"
                                    name="trans_date" id="trans_date" value="{{ old('trans_date') }}">
                                @error('trans_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <label for="" class="form-label">Status</label>
                                <input type="text" class="form-control @error('status') is-invalid @enderror"
                                    name="status" value="NEW" readonly>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        {{-- <div class="form-row"> --}}
                        {{-- <div class="form-group col-md-3">
                                <label for="inputAddress2">Trans Code</label> --}}
                        <input type="text" class="form-control @error('doc_type') is-invalid @enderror" name="doc_type"
                            value="CONS" hidden>
                        @error('doc_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        {{-- </div>
                        </div> --}}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="select2">Item No</label>
                                <select class="form-control select2 @error('item_id') is-invalid @enderror" name="item_id"
                                    id="select2">
                                    <optgroup label="Available Items">
                                        <option value="" disabled selected>-- Select Item No --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-uom="{{ $item->item_uom }}"
                                                data-current="{{ $item->current_stock ?? 0 }}"
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->item_no }} - {{ $item->item_desc }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="">Warehouse</label>
                                <select class="form-control @error('whse_code') is-invalid @enderror" name="whse_code">
                                    <option value="SF1" {{ old('whse_code') == 'SF1' ? 'selected' : '' }}>SF1</option>
                                    <option value="SF2" {{ old('whse_code') == 'SF2' ? 'selected' : '' }}>SF2</option>
                                </select>
                                @error('whse_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Location</label>
                                <select class="form-control @error('whse_loc') is-invalid @enderror" name="whse_loc">
                                    <option value="SF1 SUPPLIES" {{ old('whse_loc') == 'SF1 SUPPLIES' ? 'selected' : '' }}>
                                        SF1 SUPPLIES</option>
                                    <option value="SF2 SUPPLIES" {{ old('whse_loc') == 'SF2 SUPPLIES' ? 'selected' : '' }}>
                                        SF2 SUPPLIES</option>
                                </select>
                                @error('whse_loc')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="inputCity">Trans Quantity</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('trans_qty') is-invalid @enderror" name="trans_qty"
                                    value="{{ old('trans_qty') }}">
                                @error('trans_qty')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Stock</label>
                                <input type="text" class="form-control @error('current_stock') is-invalid @enderror"
                                    name="current_stock" value="" readonly>
                                @error('current_stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress2">UOM</label>
                                <input type="text" class="form-control @error('item_uom') is-invalid @enderror"
                                    name="item_uom" value="" readonly>
                                @error('item_uom')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="select2">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" placeholder="Masukkan catatan">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('transactions.index') }}" class="btn btn-danger mr-3">CANCEL</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">SAVE</button>
                        </div>
                    </form>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
        </div> <!-- /. col -->
    </div> <!-- /. end-section -->

    @push('scripts')
        <script>
            $(function() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    minimumResultsForSearch: 0,
                    width: '100%',
                });

                $('#select2').on('change', function() {
                    const selectedOption = $(this).find(':selected');
                    const uom = selectedOption.data('uom');
                    const stock = selectedOption.data('current');

                    $('input[name="item_uom"]').val(uom || '');
                    $('input[name="current_stock"]').val(stock ?? 0);
                });

                // Handle old value saat validasi gagal (page reload)
                const selectedOption = $('#select2').find(':selected');
                const initialUom = selectedOption.data('uom');
                const initialStock = selectedOption.data('current');

                if (initialUom) $('input[name="item_uom"]').val(initialUom);
                if (initialStock !== undefined) $('input[name="current_stock"]').val(
                    initialStock ?? 0);
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
