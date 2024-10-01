<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 06:10 PM
 *-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Marcas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Marcas</a></li>
                <li class="active-page"><a>Ver Marcas</a></li>
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
                            <h5><strong>Lista de Marcas</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            @if(userSchool()->type == "restaurant")
                                <h5>
                                    <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                                </h5>
                            @endif
                        </div>
                        <div class="col-sm-3">
                            <a href="{{route('crear-marcas')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nueva Marca</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_brands" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                @if(userSchool()->type == "restaurant")
                                    <th>Cant. Productos</th>
                                    <th>Productos Gravados</th>
                                    <th>Productos Exentos</th>
                                    <th>Cant. Proveedores</th>
                                @endif
                                <th>Edición</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $key=> $brand)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center">{{ $brand->name }}</td>
                                    @if(userSchool()->type == "restaurant")
                                    <td class="text-center">{{$brand->products->count()}}</td>
                                    <td class="text-center">{{$brand->products->where('type','gravado')->count()}}</td>
                                    <td class="text-center edit-row">{{$brand->products->where('type','exento')->count()}}</td>
                                    <td class="text-center edit-row">
                                        <a href="{{ route('ver-marcas-proveedores', $brand->token) }}"><i class="fa fa-eye"></i></a>
                                    </td>
                                    @endif
                                    <td class="text-center edit-row">
                                        <a href="{{route('editar-marca',$brand->token)}}"><i class="fa fa-pencil"></i></a>
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