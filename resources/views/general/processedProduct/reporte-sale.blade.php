<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 26/04/16
 * Time: 11:01 AM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Catálogos</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Catálogos</a></li>
                <li class="active-page"><a>Registrar Catálogo</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <h1>Buscar Ventas de Productos Elaborados</h1>
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="monthInCatalogs">Fecha Inicial</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input type="date" id="dateInCookedProduct" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="monthOutCatalogs">Fecha Final</label>
                    <input type="date" id="dateOutCookedProduct"  class="form-control" >
                </div>
            </div>

        </section>
        <div class="row text-center">
            <a href="{{route('ver-cooken')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
            <a href="#" id="recordSaleCookedProduct" data-url="productos-cocidos" class="btn btn-success">Buscar Historial Venta</a>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection