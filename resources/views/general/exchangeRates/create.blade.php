@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Tipos de Cambio</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Tipos de Cambio</a></li>
				<li class="active-page"><a>Registrar Tipos de Cambio</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="nameTask">Fecha</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input id="dateExchangeRate" class="form-control" type="date">
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="nameTask">Monto de Compra</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
				      	<input id="buyExchangeRate" class="form-control" type="text">
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="nameTask">Monto de Venta</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input id="saleExchangeRate" class="form-control" type="text">
					</div>
				</div>
			</div>
			<div class="row text-center">
				<a href="{{route('ver-tipoCambio')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
				<a href="#" id="saveExchangeRate" data-url="tipos-de-cambio" class="btn btn-success">Grabar Tipo de Cambio</a>
			</div>
		</section>
		<div class="row page col-lg-12">
			<table id="exchangeRate" class="table table-content">
				<thead>
				<tr>
					<th>Fecha</th>
					<th>Compra</th>
					<th>Venta</th>
					<th>Eliminar</th>
				</tr>
				</thead>
				<tbody>
					@foreach($exchangeRates AS $exchangeRate)
						<tr class="text-center">
							<td>{{$exchangeRate->date}}</td>
							<td>{{$exchangeRate->buy}}</td>
							<td>{{$exchangeRate->sale}}</td>
							<td><a href="" id="eliminarExchange" data-url="tipos-de-cambio" data-token="{{$exchangeRate->token}}" >
									<span class="btn btn-danger fa fa-remove"></span></a></td>
						</tr>
				    @endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		$('#exchangeRate').dataTable();
	</script>
	<script src="{{ asset('js/pages/exchangeRate.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection