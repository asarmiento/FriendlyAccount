<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 12:59 PM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Grupo de Menu del Restaurante</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Menu Restaurante</a></li>
                <li class="active-page"><a>Actualizar Grupo de Menu del Restaurante</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Nombre del Grupo</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="nameGroupMenu" class="form-control" type="text" value="{{$groupMenu->name}}" data-token="{{$groupMenu->token}}">
                    </div>
                </div>
            </div>


        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-grupo-de-menu')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="updateGroupMenu" data-url="grupo-de-menu" class="btn btn-success">Actualizar Grudo de Menu</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
