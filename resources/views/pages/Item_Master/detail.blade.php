@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <a class="h5 page-title text-success" href="{{ route('dashboard') }}"> <i
                                class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h5 class="p-4">Detail Item</h5>
                    </div>
                    <div class="col-auto">
                        <form class="form-inline">
                            <div class="form-group d-none d-lg-inline">
                                <label for="reportrange" class="sr-only">Date Ranges</label>
                                <div id="reportrange" class="px-2 py-2 text-muted">
                                    <span class="small"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-sm"><span
                                        class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                                <button type="button" class="btn btn-sm mr-2"><span
                                        class="fe fe-filter fe-16 text-muted"></span></button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- widgets -->
                <div class="row my-4">
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-barcode" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">NO ITEM</small>
                                        <h3 class="card-title mb-0">1-02-002-0048</h3>

                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-oil-can" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">TYPE ITEM</small>
                                        <h3 class="card-title mb-0">SF 805S</h3>
                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <i class="fa-solid fa-pen-ruler" style="font-size: 1.5rem; line-height: 2rem;"></i>
                                    </div>
                                    <div class="col-11">
                                        <small class="text-muted mb-1">SATUAN</small>
                                        <h3 class="card-title mb-0">KG</h3>
                                    </div>
                                </div> <!-- /. row -->
                            </div> <!-- /. card-body -->
                        </div> <!-- /. card -->
                    </div> <!-- /. col -->
                </div> <!-- end section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body px-4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>Beg. Balance</th>
                                            <th>Received</th>
                                            <th>Consume</th>
                                            <th>End Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01 October 2024</td>
                                            <td>1000</td>
                                            <td>50</td>
                                            <td>10</td>
                                            <td>1040</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>1000</th>
                                            <th>50</th>
                                            <th>10</th>
                                            <th>1040</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->
                </div> <!-- .row -->
            </div>
        </div>
    </div>
@endsection
