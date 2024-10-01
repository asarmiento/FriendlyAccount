<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 12:59 PM
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
                <li class="active-page"><a>Registrar Menu Restaurante</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Nombre Menu</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="nameMenuRestaurant" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="descriptionRawProduct">Costo</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="costoMenuRestaurant" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="descriptionRawProduct">Grupo</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <select id="groupMenuIdMenuRestaurant" class="form-control select2">
                            @foreach($groupMenus AS $groupMenu)
                            <option value="{{$groupMenu->token}}">{{$groupMenu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="tipo">¿Tipo?</label>
                    <div class="row">
                        <input id="typeMenuRestaurant" type="checkbox" name="status-checkbox" data-on-text="Bebida"  data-off-text="Comida"  data-on-color="info" data-off-color="danger" data-label-text="Activo" >
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-menuRestaurant')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveMenuRestaruants" data-url="menu-restaurante" class="btn btn-success">Grabar Menu Restaurante</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
