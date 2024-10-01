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
        <h2>Materias Primas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Materias Primas</a></li>
                <li class="active-page"><a>Ver Materias Primas</a></li>
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
                            <h5><strong>Lista de Materias Primas</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            <h5>
                                <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                            </h5>
                        </div>

                        <div class="col-sm-3">
                            <a href="{{route('crear-rawMaterials')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nueva Materia Prima</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_rawProduct" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Codigo</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Costo</th>
                                <th>Stock</th>
                                <th>Stock Sugerido</th>
                                <th>Reporte</th>
                                <th>Proveedores</th>
                                <th>Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rawProducts as $key=>$rawProduct)

                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $rawProduct->code }}</td>
                                    <td class="text-center">{{ convertTitle($rawProduct->description) }}</td>
                                    <td class="text-center">{{ convertTitle($rawProduct->brands->name) }}</td>
                                    <td class="text-center">{{ number_format($rawProduct->cost,2) }}</td>

                                    @if(is_object($rawProduct->inventory))
                                        <td class="text-center">{{number_format($rawProduct->inventory->amount,3)}}</td>
                                    @else
                                        <td class="text-center">0</td>
                                    @endif
                                    <td class="text-center">{{number_format($rawProduct->suggested,2)}}</td>
                                    <td class="text-center edit-row">
                                        <a href="#"><i class="fa fa-file-excel-o"></i></a>
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('supplier-rawMaterials',$rawProduct->token)}}"><i class="fa fa-eye"></i></a>
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('editar-rawMaterials',$rawProduct->token)}}"><i class="fa fa-pencil"></i></a>
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