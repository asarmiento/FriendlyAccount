@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Proveedores de La Marca</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Marcas</a></li>
                <li class="active-page"><a>Proveedores de la Marca</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="form-mep">
                @foreach($brand->suppliers AS $supplier)
                    <div class="col-sm-4 col-md-4">
                        {{$supplier->name}}
                    </div>
                @endforeach
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-marcas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection