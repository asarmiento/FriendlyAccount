<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/04/16
 * Time: 07:48 PM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Usuario</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Usuario</a></li>
                <li class="active-page"><a>Cambio de Contraseña</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="password">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input id="password" class="form-control" type="password" maxlength="10"  >
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="confirmPassword">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="confirmPassword" class="form-control" type="password" maxlength="10" >
                    </div>
                </div>
            </div>

        </section>
        <div class="row text-center">
            <a href="#" id="updatePassword" data-url="cambio-clave" class="btn btn-success">Actualizar Contraseña</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection