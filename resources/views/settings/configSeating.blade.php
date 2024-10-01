@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Configuración</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Configuración</a></li>
				<li class="active-page"><a>Registrar Configuración</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="typeSeatSetting">Asientos Creados</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <select id="catalogSetting" class="form-control select2">
						@foreach($seatings AS $seating )
							<option value="{{$seating->code}}">{{$seating->code}}</option>
						@endforeach
						</select>
					</div>
				</div>
			</div>
		
		</section>
		<div class="row text-center">
			<a href="#" id="searchSeatingC" data-url="configuracion" class="btn btn-success">Buscar por Codigo</a>
		</div>
	</div>
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="catalogSetting">Peridos Contables generados</label>
					<select id="catalogSetting" class="form-control select2" >
						@foreach($periods AS $period )
							<option value="{{$period->id}}">{{$period->month}} {{$period->year}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</section>
		<div class="row text-center">
			<a href="#" id="searchSeatingP" data-url="configuracion" class="btn btn-success">Buscar por Periodo</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection