@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create Item Oil</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('item-master.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Item ID</label>
                                <input type="text" class="form-control @error('item_id') is-invalid @enderror"
                                    name="item_id" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                    value="{{ old('item_id', $item->item_id) }}">
                                @error('item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Item No</label>
                                <input type="text" class="form-control @error('item_no') is-invalid @enderror"
                                    name="item_no" value="{{ old('item_no', $item->item_no) }}">
                                @error('item_no')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Item Description</label>
                            <input type="text" class="form-control @error('item_desc') is-invalid @enderror"
                                name="item_desc" value="{{ old('item_desc', $item->item_desc) }}">
                            @error('item_desc')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputAddress2">GL Class</label>
                            <input type="text" class="form-control @error('item_glclass') is-invalid @enderror"
                                name="item_glclass" value="{{ old('item_glclass', $item->item_glclass) }}">
                            @error('item_glclass')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCity">Stock</label>
                                <input type="text" class="form-control @error('current_stock') is-invalid @enderror"
                                    name="current_stock" oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                    value="{{ old('current_stock', $item->current_stock) }}">
                                @error('current_stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">UOM</label>
                                <input type="text" class="form-control @error('item_uom') is-invalid @enderror"
                                    name="item_uom" value="{{ old('item_uom', $item->item_uom) }}">
                                @error('item_uom')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
        </div> <!-- /. col -->
    </div> <!-- /. end-section -->
@endsection
