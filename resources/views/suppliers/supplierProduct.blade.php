<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 24/01/16
 * Time: 04:11 PM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Proveedores de la Materia Prima: {{$product->description}} </h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Proveedores </a></li>
                <li class="active-page"><a>Ver Proveedores de la Materia Prima: {{$product->description}} </a></li>
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
                       <div class="table table-content">
                           @foreach($suppliers AS $supplier)

                                   <div class="col-md-6">
                                    <ol type="1">{{$supplier->name}} {{$supplier->name}}
                                        @foreach($supplier->brands AS $brand)
                                        <li>{{$brand->name}}</li>
                                        @endforeach
                                    </ol>
                                </div>

                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="table-content">
                    <div class="text-center table-responsive">
                        <a href="{{route('ver-rawMaterials')}}" class="btn btn-default">Regresar</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection