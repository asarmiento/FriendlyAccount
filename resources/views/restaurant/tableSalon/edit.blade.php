<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 11:20 AM
 -->

@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Mesas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Mesas</a></li>
                <li class="active-page"><a>Editar Mesa</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameTableSalon">Nombre de la Mesa</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="nameTableSalon" class="form-control" type="text" value="{{convertTitle($table->name)}}" data-token="{{$table->token}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="barra">¿Barra?</label>
                    <div class="row">
                        @if($table->barra)
                            <input id="barra" type="checkbox" name="status-checkbox" data-on-text="Barra" data-off-text="Mesa" data-on-color="info" data-off-color="danger" data-label-text="Tipo" checked>
                        @else
                            <input id="barra" type="checkbox" name="status-checkbox" data-on-text="Barra" data-off-text="Mesa" data-on-color="info" data-off-color="danger" data-label-text="Tipo">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="color">Color</label>
                    <div class="row">
                        <select id="colorTableSalon" class="form-control">
                            <option class="rojo" value="rojo">Rojo</option>
                            <option class="amarillo" value="amarillo">Amarillo</option>
                            <option class="morado" value="morado">Morado</option>
                            <option class="azul" value="azul">Azul</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-mesas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="updateTable" data-url="mesas" class="btn btn-success">Actualizar Mesa</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection