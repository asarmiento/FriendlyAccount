<!--
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 21/08/16
 * Time: 08:36 PM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Empleados</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Empleados</a></li>
                <li class="active-page"><a>Registrar Horas Empleados</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section id="tiemeEmployess" class="row"  >
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameTask">Cedula</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                        <select id="tokenEmployess"  class="form-control select2">
                            @foreach($employess AS $employes)
                            <option value="{{$employes->token}}">{{$employes->nameComplete()}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameTask">Fecha</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                        <input id="dateEmployess"  class="form-control" size="10" readonly type="text" value="">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameTask">Hora</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                        <input id="timesEmployess"  class="form-control" size="10" readonly type="text" value="">
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <a href="{{route('ver-employess')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="save-registro-de-horas-Employess" data-url="registro-de-horas" class="btn btn-success">Grabar Empleado</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
    <script src="{{ asset('bower_components/handlebars/handlebars.min.js') }}"></script>
@endsection