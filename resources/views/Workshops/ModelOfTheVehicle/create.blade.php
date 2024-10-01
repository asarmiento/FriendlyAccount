@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		@if(userSchool()->type == "taller")
			<h2>Modelos de Vehiculos</h2>
		@else
			<h2>Modelos de Celulares</h2>
		@endif
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				@if(userSchool()->type == "taller")
					<li><a>Modelos de Vehiculos</a></li>
					<li class="active-page"><a>Registrar Modelos de Vehiculos</a></li>
				@else
					<li><a>Modelos de Celulares</a></li>
					<li class="active-page"><a>Registrar Modelos de Celulares</a></li>
				@endif
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameModelOfTheVehicle">Modelo</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-car"></i></span>
				      	<input id="nameModelOfTheVehicle" class="form-control" type="text" >
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					@if(userSchool()->type == "taller")
						<label for="brandsModelOfTheVehicle">Marca de Vehiculo</label>
					@else
						<label for="brandsModelOfTheVehicle">Marca de Celulares</label>
					@endif
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
				      	<select id="brandsModelOfTheVehicle" class="form-control">
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
			<a href="#" id="saveModelOfTheVehicle" data-url="modelo-de-vehiculo" class="btn btn-success">Grabar Modelo</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/pages/workshops/modelOfTheVehicle.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection