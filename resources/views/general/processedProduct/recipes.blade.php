<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 01/01/16
 * Time: 08:00 AM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Recetas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Recetas</a></li>
                <li class="active-page"><a>Registrar Recetas</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <h1 class="center"><center>Ingredientes de {{$cooked->name}}</center></h1>
        <section class="row">
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="rawProductRecipes">Ingredientes</label>
                    <select  id="rawProductRecipes" data-url="recetas-units"  class="form-control select2" data-type="select">
                        @foreach($rawProducts AS $rawProduct)
                            <option value="{{$rawProduct->token}}">{{convertTitle($rawProduct->description)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="amountRecipes">Cantidad</label>
                    <div class="input-group">
                        <span class="input-group-addon">#</span>
                        <input id="amountRecipes" class="form-control" data-token="{{$cooked->token}}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="unitsRecipes">Tipo de Medida</label>
                    <select id="unitsRecipes" class="form-control select2" data-type="select">
                        @foreach($units AS $unit)
                            <option value="{{$unit}}">{{convertTitle($unit)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <a href="#" id="saveRecipes" data-url="recetas" class="btn btn-info">Grabar Ingredientes</a>
                </div>
            </div>
        </section>
        <h1 class="center-block"><center>Receta de {{$cooked->name}}</center></h1>
        <section>
            <div class="col-sm-3 col-md-3">
                <label>Cantidad de P. Elaborados por Receta: {{$cooked->numberOfDishes}}</label>
           </div>
            <div class="col-sm-3 col-md-3">
                <label>Costo de P. Elaborados: {{number_format($totalrecipt,2)}}</label>
            </div>
            <div class="col-sm-3 col-md-3">
                <label>Precio de P. Elaborados: {{number_format($cooked->price,2)}}</label>
            </div>
            <div class="small-12 columns" id="list">
                <table class="table table-bordered table-hover TableDetail">
                    <thead>
                        <tr>
                            <th>Medida</th>
                            <th>Ingredienes</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="products">
                    @if(count($cooked->recipts)>0)
                        @foreach($cooked->recipts AS $recipt)
                        <tr>
                            <td>{{number_format($recipt->amount,2).' '.$recipt->units}}</td>
                            <td>{{$recipt->rawProducts->description}}</td>
                            <td  class="text-center edit-row">
                                <a id="delete_recipt" data-url="recetas" data-token="{{($recipt->token)}}" class="btn btn-danger btn-xs delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 col-md-12 text-center">
                <a href="{{route('ver-cooken')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="{{route('ver-cooken')}}" id="finRecipes" data-url="recetas" class="btn btn-success">Finalizar Recetas</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
    <script src="{{ asset('js/pages/general/processedProducts.js') }}"></script>
    <script src="{{ asset('bower_components/handlebars/handlebars.min.js') }}"></script>
@endsection
