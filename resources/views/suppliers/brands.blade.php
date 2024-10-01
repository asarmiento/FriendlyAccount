@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Proveedores Marcas</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Proveedores</a></li>
                <li class="active-page"><a>Registrar Marcas al Proveedor</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label>Nombre del Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input class="form-control" readonly value="{{$supplier->name}}" type="text">
                        <input id="tokenSupplier" value="{{$supplier->token}}" type="hidden">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="tokenBrand">Marcas</label>
                    <select id="tokenBrand" class="form-control select2">
                        @foreach($brands as $brand)
                            <option value="{{$brand->token}}">{{$brand->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-proveedores')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="saveProveedorMarcas" data-url="proveedores" class="btn btn-success">Grabar Marca</a>
            </div>
        </section>
        <section style="margin-top: 2em">
            <div class="table-responsive">
                <table id="table_supplier_brands" class="table table-bordered table-hover TableDetail" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th>Marca</th>
                            <th class="text-center">Edición</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->brands As $key =>$brand)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td>{{$brand->name}}</td>
                            <td class="text-center">
                                <button href="#" class="btn btn-danger btn-xs" id="delete_brand" data-url="proveedores" data-token="{{$brand->token}}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('js/lib/i18n/es.js') }}"></script>
@endsection