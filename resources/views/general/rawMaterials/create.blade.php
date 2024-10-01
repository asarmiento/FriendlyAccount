<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 03:24 PM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Materias Primas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Materias Primas</a></li>
                <li class="active-page"><a>Registrar Materias Primas</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Codigo del Materias Primas</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="codeRawProduct" readonly class="form-control" type="text" value="{{$code}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="descriptionRawProduct">Descripcion del Materias Primas</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="descriptionRawProduct" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="typeRawProduct">Tipo de Materias Primas</label>
                    <select class="form-control" id="typeRawProduct">
                        <option value="GRAVADO">Gravado</option>
                        <option value="EXENTO">Exento</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="brandRawProduct">Tipo Unidad/Peso/Liquido</label>
                    <select class="form-control" id="unitsRawProduct">
                        <option value="">Seleccione una Opción</option>
                        @foreach($units AS $key => $unit)
                            <option value="{{$key}}">{{convertTitle($unit)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="brandRawProduct">Marca</label>
                    <select class="form-control" id="brandRawProduct">
                        @foreach($brands AS $brand)
                            <option value="{{$brand->token}}">{{$brand->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="form-mep">
                    <label for="cocidoRawProduct">Agregar A Materias Primas</label>
                    <input id="cocidoRawProduct" type="checkbox" name="status-checkbox" data-off-text="SI" data-on-text="NO" checked data-off-color="info" data-on-color="danger" data-label-text="Tipo" >
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="form-mep">
                    <label for="priceRawProduct">Precio de Venta sin Impuestos</label>
                    <input type="decimal"  id="priceRawProduct" class="form-control" value="0" >
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-rawMaterials')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveRawProduct" data-url="materias-primas" class="btn btn-success">Grabar Materias Primas</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
