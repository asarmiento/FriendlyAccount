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
        <h2>Componentes</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Componentes</a></li>
                <li class="active-page"><a>Registrar Componente</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <h1 class="center"><center>Menu de {{$menuElement->name}}</center></h1>
        <section class="row">
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="cookedProductComponents">Componentes</label>
                    <select  id="cookedProductComponents"  class="form-control select2" data-token="{{$menuElement->token}}" data-type="select">
                        @foreach($cookedProducts AS $cookedProduct)
                            <option value="{{$cookedProduct->token}}">{{convertTitle($cookedProduct->name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="amountComponents">Cantidad</label>
                    <div class="input-group">
                        <span class="input-group-addon">#</span>
                        <input id="amountComponents"  class="form-control" data-token="{{$menuElement->token}}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="typeComponents">Tipo</label>
                    <select id="typeComponents" class="form-control">
                        <option value="Base">Base</option>
                        <option value="Adicional">Adicional</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <a href="#" id="saveComponents" data-url="componentes" class="btn btn-info">Grabar Componentes</a>
                </div>
            </div>
        </section>
        <h1 class="center-block"><center>Componentes de {{$menuElement->name}} Precio: {{number_format($menuElement->costo,2)}}</center></h1>
        <section>
            <div class="small-12 columns" id="list">
                <table class="table table-bordered table-hover TableDetail">
                    <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Componente</th>
                        <th>Tipo</th>
                        <th>Costo</th>
                        <th>Precio Venta Unitario</th>
                        <th class="text-center">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody id="components">

                    @foreach($cookedProductsAdded AS $cookedProductAdded)
                        <tr>
                            <td>{{$cookedProductAdded->amount}}</td>
                            <td>{{$cookedProductAdded->cookedProducts->name}}</td>
                            <td>{{$cookedProductAdded->type}}</td>
                            <td>{{number_format($cookedProductAdded->cost,2)}}</td>
                            <td>{{number_format($cookedProductAdded->price,2)}}</td>
                            <td  class="text-center">
                                <button href="#" class="btn btn-danger btn-xs" id="delete_component" data-url="componentes" data-token="{{base64_encode($cookedProductAdded->id)}}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                        </tr>
                        <?php ?>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>Total:</strong></td>
                        <td><strong>{{number_format($totalCost,2)}}</strong></td>
                        <td><strong>{{number_format($totalPrice,2)}}</strong></td>
                        <td  class="text-center"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 col-md-12 text-center">
                <a href="{{route('ver-menuRestaurant')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="{{route('ver-menuRestaurant')}}" id="finComponents" class="btn btn-success">Finalizar Componentes</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
    <script src="{{ asset('bower_components/handlebars/handlebars.min.js') }}"></script>
@endsection
