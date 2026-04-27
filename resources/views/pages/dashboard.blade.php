@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- widgets -->
                <div class="row my-4">
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-oil-well text-warning"></i> Total
                                            Item</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0">1168</h3>
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
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-fire text-danger"></i> Total
                                            Consumption</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0">68 KG</h3>
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
                                        <h3 class="text-muted mb-1"> <i class="fa-solid fa-truck text-info"></i> Total
                                            Received</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <h3 class="card-title mb-0">108 KG</h3>
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
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NO ITEM</th>
                                            <th>ITEM NAME</th>
                                            <th>SATUAN</th>
                                            <th>TOTAL RECEIVED</th>
                                            <th>TOTAL CONSUMPTION</th>
                                            <th>END STOCK</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>001</td>
                                            <td>Solar</td>
                                            <td>KG</td>
                                            <td>108</td>
                                            <td>68</td>
                                            <td>40</td>
                                            <td><a href="{{ route('item-master.detail', ['id' => 1]) }}"
                                                    class="btn btn-sm btn-primary">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>002</td>
                                            <td>Diesel</td>
                                            <td>KG</td>
                                            <td>200</td>
                                            <td>150</td>
                                            <td>50</td>
                                            <td><a href="{{ route('item-master.detail', ['id' => 2]) }}"
                                                    class="btn btn-sm btn-primary">Detail</a></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->

                </div> <!-- .row -->
            </div> <!-- /.col -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->

    @push('scripts')
        <script>
            $('#dataTable-1').DataTable({
                autoWidth: true,
                lengthMenu: [
                    [10, 20, 30, -1],
                    [10, 20, 30, 'All']
                ]
            });
        </script>
    @endpush
@endsection
