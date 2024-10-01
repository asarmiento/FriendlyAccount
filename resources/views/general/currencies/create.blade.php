@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Denominación de Monedas</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Denominación de Monedas</a></li>
				<li class="active-page"><a>Registrar Denominación de Monedas</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="codeDegree">Nombre de la Moneda</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
				      	<input id="nameCurrencies" class="form-control" type="text" maxlength="30">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameDegree">Valor de la Moneda</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
				      	<input id="valueCurrencies" class="form-control" type="number">
					</div>
				</div>
			</div>

		</section>
		<div class="row text-center">
			<a href="{{route('ver-monedas')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="saveCurrencies" data-url="denominacion-monedas" class="btn btn-success">Grabar La Moneda</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection