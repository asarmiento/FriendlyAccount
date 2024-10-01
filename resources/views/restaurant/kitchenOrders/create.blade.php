<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 30/12/15
 * Time: 02:12 PM
-->

@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Pedidos de Cocina</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Pedidos de cocina</a></li>
                <li class="active-page"><a>Registrar Pedidos de cocina</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="table-data">
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_kitchenOrders" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Unidad de Medida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rawProducts as $key => $rawProduct)
                                    <tr class="product">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="menu_name">{!! $rawProduct->description !!}</td>
                                        <td class="text-center amount" contenteditable>0</td>
                                        <td class="text-center" contenteditable width="200">
                                            <select class="form-control select2 unit">
                                                @foreach ($units as $unit)
                                                    <option value="{{$unit}}">{{$unit}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <input type="hidden" class="token" value="{{$rawProduct->token}}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row text-center" style="padding-bottom: 1em;">
                    <a href="{{route('ver-ingresos-inventario')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                    <a href="#" id="saveKitchenOrder" data-url="pedidos-de-cocina" class="btn btn-success">Grabar Pedido de Cocina</a>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
@endsection
