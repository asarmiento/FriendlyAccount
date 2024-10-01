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
        <h2>Productos Elaborado</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Productos Elaborado</a></li>
                <li class="active-page"><a>Ver Productos Elaborado</a></li>
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
                            <h5><strong>Lista de Productos Elaborado</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            @if(userSchool()->type=='restaurant')
                            <h5>
                                <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                            </h5>
                            @endif
                        </div>

                        <div class="col-sm-3">
                            <a href="{{route('crear-cooked')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo Productos Elaborado</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_processedProduct" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Codigo</th>
                                <th>Descripción</th>
                                @if(userSchool()->regime_type == 'tradicional')
                                    <td class="text-center">Precio</td>
                                    <td class="text-center">Impuesto</td>
                                @else
                                    <td class="text-center">Precio</td>
                                @endif
                                <th>Stock Sugerido</th>
                                <th>Reporte</th>
                                <th>Receta</th>
                                <th>Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($processedProducts as $key=>$processedProduct)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $processedProduct->code }}</td>
                                    <td class="text-center">{{ convertTitle($processedProduct->name) }}</td>
                                    @if(userSchool()->regime_type == 'tradicional')
                                        <td class="text-center">{{number_format($processedProduct->price,0)}}</td>
                                        <td class="text-center">{{number_format($processedProduct->price*(userSchool()->tax_rate/100),0)}}</td>
                                    @else
                                        <td class="text-center">{{number_format($processedProduct->price+($processedProduct->price*(userSchool()->tax_rate/100)),0)}}</td>
                                    @endif
                                    <td class="text-center">{{number_format($processedProduct->suggested,0)}}</td>
                                    <td class="text-center edit-row">
                                        <a href="#"><i class="fa fa-file-excel-o"></i></a>
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('crear-recipes',$processedProduct->token)}}"><i class="fa fa-eye"></i></a>
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('edit-cooked',$processedProduct->token)}}"><i class="fa fa-pencil"></i></a>
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
    <script src="{{ asset('/js/pages/general/processedProducts.js') }}"></script>
@endsection