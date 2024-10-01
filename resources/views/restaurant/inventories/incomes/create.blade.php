<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 05:02 PM
-->

@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/jqueryui/themes/cupertino/jquery-ui.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Ingreso de Inventario</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Ingreso de Inventario</a></li>
                <li class="active-page"><a>Registrar Ingreso de Inventario</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row Inventories">
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="supplierIncome">Proveedor</label>
                    <select id="supplierIncome" class="form-control select2" data-type="select">
                        @foreach($suppliers AS $supplier)
                            <option value="{{$supplier->token}}">{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="methodIncome">Forma de Pago</label>
                    <select id="methodIncome" class="form-control" data-type="select">
                        @foreach($paymentMethods AS $paymentMethod)
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="dateIncome">Fecha Vencimiento</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="dateIncome" class="form-control datepicker" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="invoiceIncome">N° de Factura</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="invoiceIncome" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="referenceIncome">Referencia</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="referenceIncome" class="form-control" type="text" value="{{$ferencies}}" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="totalIncome">Total</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="totalIncome" class="form-control" type="text" readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <button type="button" id="add" class="btn btn-info">
                        <i class="fa fa-plus"></i> Agregar Item
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns" id="list">
                    <table class="table table-bordered table-hover TableDetail" id="print">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Units</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Costo</th>
                                <th>Descuento (%)</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody id="products">
                            <tr class="item">
                                <td class="text-center" contenteditable>
                                    <button href="#" class="btn btn-danger btn-xs delete" data-url="asientos-auxiliares">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                                <td class="code" contenteditable></td>
                                <td class="description" contenteditable></td>
                                <td class="units"></td>
                                <td class="type"></td>
                                <td class="amount" contenteditable></td>
                                <td class="cost" contenteditable></td>
                                <td class="discount" contenteditable></td>
                                <td class="text-center total"></td>
                                <input type="hidden" class="token">
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">Subtotal Gravado:</td>
                                <td class="subtotal_gravado reset"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">Subtotal Excento:</td>
                                <td class="subtotal_excento reset"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">Subtotal</td>
                                <td class="subtotal reset"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">IVA</td>
                                <td id="iva" class="iva reset"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">Descuento Total</td>
                                <td id="discount_total" class="reset"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="1">Total a pagar</td>
                                <td id="total" class="total reset"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-ingresos-inventario')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveInventoriesIncome" data-url="ingresos-inventario" class="btn btn-success">Grabar Inventario</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('bower_components/handlebars/handlebars.min.js') }}"></script>
    <script src="{{ asset('bower_components/jqueryui/jquery-ui.min.js') }}"></script>
@endsection