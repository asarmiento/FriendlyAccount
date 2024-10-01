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
        <h2> Cliente</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Cliente</a></li>
                <li class="active-page"><a>Editar Datos del Cliente</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Cedula</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="charterCustomer" class="form-control" data-token="{{$customer->token}}" type="text" value="{{$customer->charter}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Primer Nombre</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="fnameCustomer" class="form-control" type="text" value="{{$customer->fname}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Segundo Nombre</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="snameCustomer" class="form-control" type="text"  value="{{$customer->sname}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Primer Apellido</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="flastCustomer" class="form-control" type="text"  value="{{$customer->flast}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Segundo Apellido</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="slastCustomer" class="form-control" type="text"  value="{{$customer->slast}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Telefono/Celular</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="phoneCustomer" class="form-control" type="text"  value="{{$customer->phone}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Email</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="emailCustomer" class="form-control" type="text" value="{{$customer->email}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeRawProduct">Dirección</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <textarea id="addressCustomer" class="form-control" rows="10" cols="5" >

                        </textarea>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-clientes')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="updateCustomer" data-url="clientes" class="btn btn-success">Actualizar Cliente</a>
            </div>
        </section>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
