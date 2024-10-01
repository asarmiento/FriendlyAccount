<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 07:48 PM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Materia Prima</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Materia Prima</a></li>
                <li class="active-page"><a>Editar Materia Prima</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameBrand">Codigo del Materia Prima</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="codeRawProduct" class="form-control" type="text" value="{{$rawProducts->code}}" data-token="{{$rawProducts->token}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameBrand">Descripcion del Materia Prima</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="descriptionRawProduct" class="form-control" type="text"  value="{{$rawProducts->description}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameBrand">Tipo de Materia Prima</label>
                    
                        <select class="form-control" id="typeRawProduct">
                            <option value="{{$rawProducts->type}}">{{convertTitle($rawProducts->type)}}</option>
                            <option value="gravado">Gravado</option>
                            <option value="exento">Exento</option>
                        </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="brandRawProduct">Tipo Unidad/Peso/Liquido</label>
                    <select class="form-control" id="unitsRawProduct">
                        <option value="{{$rawProducts->units}}">{{convertTitle($rawProducts->units)}}</option>
                        @foreach($units AS $unit)
                            <option value="{{$unit}}">{{convertTitle($unit)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameBrand">Marca</label>
                    <select class="form-control" id="brandRawProduct">
                        <option value="{{$rawProducts->brands->token}}">{{$rawProducts->brands->name}}</option>
                            @foreach($brands AS $brand)
                                <option value="{{$brand->token}}">{{$brand->name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-rawMaterials')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="updateRawProduct" data-url="materias-primas" class="btn btn-success">Grabar Materia Prima</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
