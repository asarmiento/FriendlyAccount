@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Proveedores</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Proveedores</a></li>
                <li class="active-page"><a>Actualizar Proveedor</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameMenu">Codigo del Proveedores</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="codeSupplier" class="form-control" readonly value="{{ convertTitle($supplier->code) }}"  type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="nameMenu">Nombre del Proveedores</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="nameSupplier" class="form-control" type="text" value="{{ convertTitle($supplier->name) }}" data-token="{{ $supplier->token }}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="urlMenu">Cedula del Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                        <input id="charterSupplier" class="form-control" value="{{ convertTitle($supplier->charter) }}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="iconMenu">Telefono del Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                        <input id="phoneSupplier" class="form-control" value="{{ convertTitle($supplier->phone) }}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="emailSupplier">Email del Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                        <input id="emailSupplier" class="form-control" value="{{ convertTitle($supplier->email) }}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="iconMenu">Limite de Credito</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                        <input id="amountSupplier" class="form-control" type="text"  value="{{ convertTitle($supplier->amount) }}" >
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="addressSupplier">Dirección del Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                        <input id="addressSupplier" class="form-control" value="{{ convertTitle($supplier->address) }}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="iconMenu">Nombre del Contacto</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input id="contactSupplier" class="form-control" value="{{ convertTitle($supplier->contact) }}" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="iconMenu">Telefono del Contacto</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                        <input id="phoneContactSupplier" class="form-control" value="{{ convertTitle($supplier->phoneContact) }}" type="text">
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row text-center">
                <a href="{{route('ver-proveedores')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
                <a href="#" id="updateProveedor" data-url="proveedores" class="btn btn-success">Actualizar Proveedor</a>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection