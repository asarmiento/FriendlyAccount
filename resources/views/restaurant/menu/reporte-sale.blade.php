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
        <h2>Menu Restaurante</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Menu Restaurante</a></li>
                <li class="active-page"><a>Ver ventas por Menu Restaurante</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <h1>Buscar Ventas de Menu Restaurante</h1>
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="monthInCatalogs">Fecha Inicial</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input type="date" id="dateInMenuRestaurant" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="monthOutCatalogs">Fecha Final</label>
                    <input type="date" id="dateOutMenuRestaurant"  class="form-control" >
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="form-mep">
                    <label for="saleMenuRestaurant">Ventas</label>
                    <input type="checkbox" id="saleMenuRestaurant"  class="form-default" >
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="form-mep">
                    <label for="receiptMenuRestaurant">Con Recetas</label>
                    <input type="checkbox" id="receiptMenuRestaurant"  class="form-default" >
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="form-mep">
                    <label for="menuMenuRestaurant">Menu Completo</label>
                    <input type="checkbox" id="menuMenuRestaurant"  class="form-default" >
                </div>
            </div>
        </section>
        <div class="row text-center">
            <a href="{{route('ver-menuRestaurant')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
            <a href="#" id="recordSaleMenuRestaurant" data-url="menu-restaurante" class="btn btn-success">Buscar Historial Venta</a>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection