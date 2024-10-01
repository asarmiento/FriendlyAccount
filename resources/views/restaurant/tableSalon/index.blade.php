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
        <h2>Mesas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Mesas</a></li>
                <li class="active-page"><a>Ver Mesas</a></li>
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
                            <h5><strong>Lista de Mesas</strong></h5>
                        </div>
                        <div class="col-sm-3">
                            <h5>
                                <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
                            </h5>
                        </div>

                        <div class="col-sm-3">
                            <a href="{{route('crear-mesas')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nueva Mesa</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_tableSalon" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Barra</th>
                                <th>Edición</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tables as $key=> $table)
                                <tr class="<?php echo $table->color; ?>">
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $table->name }}</td>
                                    <td class="text-center">
                                        @if($table->barra)
                                            X
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('editar-mesa',$table->token)}}"><i class="fa fa-pencil"></i></a>
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