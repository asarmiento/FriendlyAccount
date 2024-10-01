@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Catálogos</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Catálogos</a></li>
				<li class="active-page"><a>Registrar Catálogo</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<h1>Buscar Estado de Cuenta</h1>
		<section class="row">
			<div class="col-sm-7 col-md-7">
				<div class="form-mep">
					<label for="nameCatalogs">Cuenta del Catalogo</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
						<select id="nameCatalogs" class="form-control select2">
							@foreach($catalogs AS $catalog)
								<option value="{{$catalog->token}}">{{$catalog->code}}  {{ucwords(strtolower($catalog->name))}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="monthInCatalogs">Mes Inicial</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
						<select id="monthInCatalogs" class="form-control">
							@foreach($periods AS $period)
							<option value="{{$period->token}}">{{$period->month}}-{{$period->year}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-mep">
					<label for="monthOutCatalogs">Mes Final</label>
					<select id="monthOutCatalogs" class="form-control">
						@foreach($periods AS $period)
							<option value="{{$period->token}}">{{$period->month}}-{{$period->year}}</option>
						@endforeach
					</select>
				</div>
			</div>

		</section>
		<div class="row text-center">
			<a href="{{route('ver-catalogos')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="searchCatalog" data-url="reportes" class="btn btn-success">Buscar Estado de Cuenta</a>
		</div>
	</div>
@stop

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection