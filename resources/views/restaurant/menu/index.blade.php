<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/01/16
 * Time: 09:14 PM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Menu del Restaurante</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Menu del Restaurante</a></li>
                <li class="active-page"><a>Ver Menu del Restaurante</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="table-data">
                <div class="table-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5><strong>Lista de Menu del Restaurante</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            <h5>
                                <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                            </h5>
                        </div>

                        <div class="col-sm-3">
                            <a href="{{route('crear-menuRestaurant')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo Menu del Restaurante</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_menuRestaurant" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>Grupo Menu</th>
                                <th>Nombre</th>
                                <th>Costo</th>
                                <th>Total Ventas</th>
                                <th>Ventas Semanal</th>
                                <th>Prom. Venta Diaria</th>
                                <th>Editar</th>
                                <th>Componentes</th>
                                <th>Eliminar</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($menuRestaurants AS $menuRestaurant)

                                   @if($menuRestaurant->cookedProduct->count()>0)
                                      <tr>
                                    @else
                                      <tr style="background: #f2ae9c">
                                    @endif
                                        <td class="text-center">{{$menuRestaurant->groupMenus->name}}</td>
                                        <td class="text-center">{{$menuRestaurant->name}}</td>
                                        <td class="text-center">{{number_format($menuRestaurant->costo,2)}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <a href="{{route('edit-menu',$menuRestaurant->token)}}"><i class="fa fa-pencil"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('componente-menuRestaurant',$menuRestaurant->token)}}"><i class="fa fa-eye"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <a id="eliminar-menu-restaurante" data-token="{{$menuRestaurant->token}}" data-url="menu-restaurante">
                                                <i class="fa fa-times" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection