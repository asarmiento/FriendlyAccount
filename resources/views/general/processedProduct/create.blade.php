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
        <h2>Productos Elaborados</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Productos Elaborados</a></li>
                <li class="active-page"><a>Registrar Productos Elaborados</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeProcessedProduct">Codigo del Producto Elaborados</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="codeProcessedProduct" readonly class="form-control" type="text" value="{{$code}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeProcessedProduct">Nombre del Producto Elaborados</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="nameProcessedProduct" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">

                    <label for="codeProcessedProduct">Precio de orden Producto Elaborados</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="priceProcessedProduct" class="form-control" type="number">
                    </div>
                    <label class="label-warning"><strong>Debe digitar el precio de venta con impuesto de venta</strong></label>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeProcessedProduct">Cantidad de P. Elaborados x Receta</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="numberOfDishesProcessedProduct" class="form-control" type="number">
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-cooken')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveProcessedProduct" data-url="productos-elaborados" class="btn btn-success">Grabar Producto Elaborados</a>
            </div>
        </section>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('js/pages/general/processedProducts.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection
