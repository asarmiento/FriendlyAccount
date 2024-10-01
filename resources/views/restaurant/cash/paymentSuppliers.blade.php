@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/ladda-bootstrap/dist/ladda-themeless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('content')
    <section class="row card-restaurant">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Pago a Proveedores
                </div>
                <div class="panel-body">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-mep">
                            <label for="date_ini">Proveedores</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                <select id="supplierPaymentSuppler" class="form-control select2">
                                    @foreach($suppliers AS $supplier)
                                        <option value="{{$supplier->token}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-mep">
                            <label for="date_end">Numero de Factura</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                <input id="numberPaymentSuppler" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-mep">
                            <label for="date_end">Monto de Factura</label>
                            <div>
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <input id="amountPaymentSuppler" class="form-control" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12">
                        <div class="form-mep text-center">
                            <a id="savePaymentSuppliers" data-url="caja" href="#" class="btn btn-success"><span class="fa fa-floppy-o"></span> Guardar Pago</a>
                            <a href="{{route('ver-salon')}}" class="btn btn-default"><span class="fa fa-arrow-left"></span> Regresar</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-content">
            <table class="table" id="table_Paymentsupplier">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Numero factura</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentSuppliers AS $paymentSupplier)
                    <tr>
                        <td>{{$paymentSupplier->supplier->name}}</td>
                        <td>{{$paymentSupplier->number}}</td>
                        <td>{{number_format($paymentSupplier->amount,2)}}</td>
                        <td>{{$paymentSupplier->date}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div id="modalOrders" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("#table_Paymentsupplier").dataTable();
    </script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/spin.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/ladda.min.js') }}"></script>
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
@endsection