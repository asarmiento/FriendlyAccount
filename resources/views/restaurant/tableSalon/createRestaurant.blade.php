@extends('layouts.app')

@section('styles')
    <style>
        .azul{background-color: #0000FF;
            color: #ffffff;}


        .rojo{ background-color: #F2BDAC;
            color: #ffffff;}

        .verde{ background-color: #B6D8B1;
            color: #000;}

        .morado{background-color: #803fd4;
            color: #ffffff;}


        .amarillo{background-color: #FFFF00;
            color: #000000;}

    </style>
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Mesas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Mesas</a></li>
                <li class="active-page"><a>Registrar Mesa</a></li>
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
                        <input id="nameTableSalon" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="barra">¿Con I.V.S. o Sin I.V.S.?</label>
                    <div class="row">
                        <input id="barra" type="checkbox" name="status-checkbox" data-off-text="SIN" data-on-text="CON" checked data-on-color="info" data-off-color="danger" data-label-text="Tipo" >
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="color"></label>
                    <div class="row">
                        <input type="hidden" id="colorTableSalon" value="verde">
                        <input type="hidden" id="restaurantTableSalon" value="si">
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-mesas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveTable" data-url="mesas" class="btn btn-success">Grabar Mesa</a>
                <a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection