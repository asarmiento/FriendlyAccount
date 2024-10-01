@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Tipos de Empresas</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Tipos de Empresas</a></li>
				<li class="active-page"><a>Editar Tipos de Empresas</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="codeDegree">Tipo de Empresa</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
				      	<input id="nameTypeOfCompany" class="form-control" type="text"  value="{{ convertTitle($typeOfCompany->name) }}" data-token="{{$typeOfCompany->token}}">
					</div>
				</div>
			</div>


		</section>
		<div class="row text-center">
			<a href="{{route('ver-empresas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="updateTypeOfCompany" data-url="tipo-de-empresa" class="btn btn-success">Actualizar Tipo de Empresa</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/pages/general/typeOfCompany.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection