<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 06:10 PM
 *-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Ingresos de Inventario</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Ingresos de Inventario</a></li>
                <li class="active-page"><a>Ver Ingresos de Inventario</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="table-data">
                <div class="table-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5><strong>Lista de Ingresos de Inventario</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{route('crear-ingresos-inventario')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo Inventario</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_inventaryIncome" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>Proveedor</th>
                                <th>N° Factura</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($buys as $buy)
                                <tr>
                                    <td class="text-center">{{$buy->supplier->name}}</td>
                                    <td class="text-center">{{$buy->reference}}</td>
                                    <td class="text-center">{{$buy->invoice->date}}</td>
                                    <td class="text-center">{{$buy->balance}}</td>
                                    <td class="text-center edit-row">
                                        <a target="_blank" href="{{route('report-pdf-buy', $buy->token)}}"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection