@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Periodos Contables</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Periodos Contables</a></li>
				<li class="active-page"><a>Registrar Periodo Contable</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<form method="post" action="{{route('reports-student')}}" target="_blank"  >
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label>Seleccione un Estudiante :</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				      	<select name="student" class="form-control select2">
							@foreach($students AS $year)
							<option value="{{$year->token}}">{{$year->book.' '.$year->nameComplete()}}</option>
							@endforeach
						</select>
						{{csrf_field()}}
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label>Seleccione un año :</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				      	<select name="year" class="form-control" select2>
							@foreach($years AS $year)
							<option value="{{$year}}">{{$year}}</option>
							@endforeach
						</select>
						{{csrf_field()}}
					</div>
				</div>
			</div>
		</section>
		<div class="row text-center">
			<input type="submit" value="Generar Reporte" class="btn btn-primary">
			</div>
		</form>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection