@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">

@endsection

@section('page')
	<aside class="page"> 
		<h2>Lista para Conciliar</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Lista para Conciliar</a></li>
				<li class="active-page"><a>Ver Lista para Conciliar</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="table-data">
				<div class="table-header">
					<div class="row">
						<div class="col-sm-6">
							<h5><strong>Lista de Asientos</strong></h5>
						</div>
						<div class="col-sm-6">

						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<form action="{{route('conciliacion-post')}}" method="post" >
							{{csrf_field()}}
						<div class="col-md-4">
							<label>Tipo de Control Auxiliar</label>
							<select class="form-control select2" name="auxiliar">
								<option>Seleccione un Auxiliar</option>
								@foreach($controls As $control)
								<option value="{{$control->id}}">{{$control->name}}</option>
							    @endforeach
							</select>
						</div>
						<div class="col-md-4">
							<label>Periodo</label>
							<select class="form-control select2" name="period">
								<option>Seleccione un Periodo</option>
								@foreach($periods As $period)
									<option value="{{$period->id}}">{{$period->period()}}</option>
								@endforeach
							</select>
						</div>
							<div class="col-md-4">
								<label></label>
								<input type="submit" class="btn btn-block" value="Buscar">
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
@endsection
