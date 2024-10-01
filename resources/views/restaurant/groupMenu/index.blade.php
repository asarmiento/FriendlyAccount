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
        <h2>Grupos de Menu</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Grupos de Menu</a></li>
                <li class="active-page"><a>Ver Grupos de Menu</a></li>
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
                            <h5><strong>Lista de Grupos de Menu</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            <h5>
                                <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                            </h5>
                        </div>

                        <div class="col-sm-3">
                            <a href="{{route('crear-grupo-de-menu')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo Grupos de Menu</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_groupMenu" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad de Menu</th>
                                <th>Total Ventas</th>
                                <th>Ventas Semanal</th>
                                <th>Prom. Venta Diaria</th>
                                <th>Editar</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($groupMenus AS $groupMenu)
                                    <tr>
                                        <td>{{$groupMenu->name}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><a href="{{route('editar-grupo-de-menu',$groupMenu->token)}}"><i class="fa fa-pencil"></i></a></td>
                                        <td><a href=""><i class="fa fa-eye"></i></a></td>
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