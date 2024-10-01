<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 12:51 PM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Comisionista</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Comisionista</a></li>
                <li class="active-page"><a>Registrar Comisionista</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input id="nameCommissionAgent" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Porcentage de Comision</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="commissionCommissionAgent" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-commission')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveCommissionAgent" data-url="comisionista" class="btn btn-success">Grabar Comisionista</a>
            </div>
        </section>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
