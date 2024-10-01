@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Tipos de Companias</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Tipos de Companias</a></li>
				<li class="active-page"><a>Registrar Tipos de Companias</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="codeDegree">Tipo de compania</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
				      	<input id="nameTypeOfCompany" class="form-control" type="text" maxlength="80">
					</div>
				</div>
			</div>

		</section>
		<div class="row text-center">
			<a href="{{route('ver-empresas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="saveTypeOfCompany" data-url="tipo-de-empresa" class="btn btn-success">Grabar Tipo de Empresa</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/pages/general/typeOfCompany.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection