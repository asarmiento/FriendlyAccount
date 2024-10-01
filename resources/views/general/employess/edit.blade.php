@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Empleados</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Empleados</a></li>
				<li class="active-page"><a>Editar Empleado</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Cedula</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="charterEmployess" class="form-control" type="text" value="{{$employe->charter}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Primer Nombre</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="fnameEmployess" class="form-control" type="text" value="{{$employe->fname}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Segundo Nombre</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="snameEmployess" class="form-control" type="text" value="{{$employe->sname}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Primer Apellido</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="flastEmployess" class="form-control" type="text" value="{{$employe->flast}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Segundo Apellido</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="slastEmployess" class="form-control" type="text" value="{{$employe->slast}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Telefono</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="phoneEmployess" class="form-control" type="text" value="{{$employe->phone}}">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="nameTask">Correo Electronico</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tasks"></i></span>
						<input id="emailEmployess" class="form-control" type="text" value="{{$employe->email}}">
					</div>
				</div>
			</div>
		</section>
		<div class="row text-center">
			<a href="{{route('ver-employess')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="updateEmployess" data-url="empleado" data-token="{{$employe->token}}" class="btn btn-success">Actualizar Empleado</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection