@extends('layouts.template')
@section('content')
    <div class="row d-flex justify-content-md-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Transfer Request</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('transfer-requests.store') }}">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="">Tanggal Transaksi</label>
                                <input type="date" class="form-control @error('trans_date') is-invalid @enderror"
                                    name="trans_date" id="trans_date" value="{{ old('trans_date') }}">
                                @error('trans_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="">Item</label>
                                <select class="form-control select2-item @error('item_id') is-invalid @enderror"
                                    name="item_id" id="select2-item">
                                    <option value="" selected disabled>-- Pilih Item --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->item_id }}"
                                            {{ old('item_id') == $item->item_id ? 'selected' : '' }}
                                            data-id="{{ $item->id }}"> {{ $item->item_no }} - {{ $item->item_desc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="">Akan Disimpan Di</label>
                                <select
                                    class="form-control select2-warehouse @error('source_warehouse_id') is-invalid @enderror"
                                    name="source_warehouse_id" id="select2-warehouse">
                                    <option value="" selected disabled>-- Pilih Gudang --</option>
                                    @foreach ($source_warehouses as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('source_warehouse_id') == $item->id ? 'selected' : '' }}
                                            data-id="{{ $item->id }}">
                                            {{ $item->name }} - {{ $item->tag }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source_warehouse_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputCity">Quantity Transaksi</label>
                                <input type="number" step="0.1" min="0" pattern="^\d+(\.\d{1})?$"
                                    class="form-control @error('trans_qty') is-invalid @enderror" name="trans_qty"
                                    value="{{ old('trans_qty') }}">
                                @error('trans_qty')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress2">Stok pada Gudang</label>
                                <input type="text" class="form-control @error('current_stock') is-invalid @enderror"
                                    name="current_stock" value="" readonly>
                                @error('current_stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress2">UOM</label>
                                <input type="text" class="form-control @error('item_uom') is-invalid @enderror"
                                    name="item_uom" value="" readonly>
                                @error('item_uom')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <a href="{{ route('transactions.index') }}" class="btn btn-danger mr-3">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(function() {
                $('.select2-item').select2({
                    theme: 'bootstrap4',
                    minimumResultsForSearch: 0,
                    width: '100%',
                });
                $('.select2-warehouse').select2({
                    theme: 'bootstrap4',
                    minimumResultsForSearch: 0,
                    width: '100%',
                });
            });
        </script>
        <script>
            function fatchStock() {
                const selectedItem = $('#select2-item').find(':selected');
                const selectedWarehouse = $('#select2-warehouse').find(':selected');

                const itemDataId = selectedItem.data('id');
                const warehouseDataId = selectedWarehouse.data('id');

                if (!itemDataId || !warehouseDataId) {
                    $('input[name="current_stock"]').val(0);
                    return;
                }

                $.get("{{ route('transfer-requests.get-stock') }}", {
                    item_id: itemDataId,
                    warehouse_id: warehouseDataId
                }, function(res) {
                    $('input[name="item_uom"]').val(res.uom || '');
                    $('input[name="current_stock"]').val(res.stock ?? 0);
                }).fail(function(xhr) {
                    console.error('Gagal ambil stock:', xhr.responseJSON);
                    $('input[name="current_stock"]').val(0);
                    $('input[name="item_uom"]').val('');
                });
            }

            $('#select2-item, #select2-warehouse').on('change', function() {
                fatchStock();
            });

            $(document).ready(function() {
                if ($('#select2-item').val() && $('#select2-warehouse').val()) {
                    fatchStock();
                }
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
