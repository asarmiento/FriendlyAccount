<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 11:53 AM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Clientes</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Clientes</a></li>
                <li class="active-page"><a>Ver Clientes</a></li>
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
                            <h5><strong>Lista de Clientes</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{route('crear-clientes')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo Cliente</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-responsive">
                        <table id="table_customer" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Cedula</th>
                                <th>Nombre Cliente</th>
                                <th>Telefono/Celular</th>
                                <th>Saldo</th>
                                <th>Historial</th>
                                <th>Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $key=>$customer)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $customer->charter }}</td>
                                    <td class="text-center">{{ convertTitle($customer->nameComplete()) }}</td>
                                    <td class="text-center">{{ convertTitle($customer->phone) }}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><a href="{{route('editar-clientes',$customer->token)}}"><i class="fa fa-pencil"></i></a></td>

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