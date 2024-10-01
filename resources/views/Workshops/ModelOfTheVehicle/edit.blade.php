@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Modelos de Vehiculos</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Modelos de Vehiculos</a></li>
				<li class="active-page"><a>Editar Modelos de Vehiculos</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="abbreviationTypeSeat">Modelo</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-car"></i></span>
						<input id="nameModelOfTheVehicle" class="form-control" type="text" data-token="{{$modelOfTheVehicle->token}}" value="{{$modelOfTheVehicle->name}}" >
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTypeSeat">Marca</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
						<select id="brandsModelOfTheVehicle"  class="form-control">
							<option value="{{$modelOfTheVehicle->brand->token}}">{{$modelOfTheVehicle->brand->name}}</option>
							@foreach($brands AS $brand)
							<option value="{{$brand->token}}">{{$brand->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</section>
		<div class="row text-center">
			<a href="{{route('ver-modelo-de-vehiculo')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="updateModelOfTheVehicle" data-url="modelo-de-vehiculo" class="btn btn-success">Actualizar Modelo de Vehiculos</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection