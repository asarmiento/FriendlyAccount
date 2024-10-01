@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/ladda-bootstrap/dist/ladda-themeless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endsection

@section('content')
    <section class="row card-restaurant">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cierre de Caja
                </div>
                <div class="panel-body">
                    <div class="col-sm-4 col-md-4">
                        <div class="form-mep hidden" >
                            <label for="date_ini">Fecha Inicial</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input id="date_ini" class="form-control datepicker" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="form-mep hidden">
                            <label for="date_end">Fecha Final</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input id="date_end" class="form-control datepicker" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="form-mep">
                            <p style="margin:3px 0 0 0;">&nbsp;</p>
                            <a id="searchCash" href="#"  class="btn btn-success ladda-button " data-style="expand-left">
                                <span class="ladda-label"><i class="fa fa-search"></i>Buscar Facturas de Corte</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="col-sm-5 col-md-5">
                        <div class="form-mep">
                            <h4 class="bold">Reporte Preliminar</h4>
                            <div>
                                @if(userSchool()->regime_type=='tradicional')
                                <aside class="row">
                                    <span class="pull-left">Ventas Gravadas</span>
                                    <span class="pull-right" id="taxed_sales">0.00</span>
                                </aside>
                                <aside class="row">
                                    <span class="pull-left">Impuesto de Ventas</span>
                                    <span class="pull-right" id="iva">0.00</span>
                                </aside>
                                @else
                                    <aside class="row">
                                        <span class="pull-left">Total de Ventas</span>
                                        <span class="pull-right" id="taxed_sales">0.00</span>
                                    </aside>
                                @endif
                                <aside class="row">
                                    <span class="pull-left">Impuesto de Servicio</span>
                                    <span class="pull-right" id="service">0.00</span>
                                </aside>
                                <aside class="row totals">
                                    <span class="pull-left bold">Total de Ventas</span>
                                    <span class="pull-right bold" id="totalSales">0.00</span>
                                </aside>
                                <aside class="row">
                                    <span class="pull-left ">Total Pago a Proveedores</span>
                                    <span class="pull-right " id="payment_supplier">0.00</span>
                                </aside>
                                <aside class="row totals">
                                    <span class="pull-left bold">Total de Efectivo</span>
                                    <span class="pull-right bold" id="total_sales">0.00</span>
                                </aside>
                            </div>
                            <h4 class="bold">Detalle</h4>
                            <div>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <aside class="row">
                                        <span class="pull-left">{{$paymentMethod->name}}</span>
                                        <span class="pull-right" id="payment_{{ $paymentMethod->id }}">0.00</span>
                                    </aside>
                                @endforeach
                                <br>
                                <aside class="row">
                                    <span class="pull-left">Faltante</span>
                                    <span class="pull-right" id="missing">0.00</span>
                                </aside>
                                <br>
                                {{-- <aside class="row hide">
                                    <a href="#" id="view_sale">Ver detalle de ventas.</a>
                                </aside> --}}
                            </div>
                            <aside class="row margin-top-1">
                                <span class="pull-left">Arqueo de Caja</span>
                                <span class="pull-right" id="cash">0.00</span>
                            </aside>
                            <aside class="row totals margin-top-1">
                                <span class="pull-left bold">Balance</span>
                                <span class="pull-right bold" id="leftover">0.00</span>
                            </aside>

                        </div>
                    </div>
                    <div class="col-sm-7 col-md-7">
                        <div class="form-mep">
                            <h4 class="bold">Arqueo Físico</h4>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-mep">
                                <label for="currencies">Monedas</label>
                                <select id="currencies" class="form-control" data-type="select">
                                    @foreach($currencies as $currencie)
                                        <option value="{{$currencie->id}}" data-value="{{$currencie->value}}">{{ convertTitle($currencie->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-mep">
                                <label for="amount">Cantidad:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                    <input id="amount" class="form-control" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-mep">
                                <p style="margin:3px 0 0 0;">&nbsp;</p>
                                <a id="addCurrencie" href="#" class="btn btn-info">
                                    <i class="fa fa-plus"></i> Agregar Monedas
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-mep">
                                <table class="table table-bordered table-hover TableDetail">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Moneda</th>
                                            <th>Cantidad</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="currencie_detail">

                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th>Total:</th>
                                            <th id="total_currencies">0</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-mep text-center">
                            <a id="finshCourt" href="#" class="btn btn-success"><span class="fa fa-floppy-o"></span> Finalizar Arqueo</a>
                            <a id="saveCourt" href="#" class="btn btn-success"><span class="fa fa-floppy-o"></span> Grabar Corte</a>
                            <a href="{{route('ver-salon')}}" class="btn btn-default"><span class="fa fa-arrow-left"></span> Regresar</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <div id="modalOrders" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/spin.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/ladda.min.js') }}"></script>
@endsection