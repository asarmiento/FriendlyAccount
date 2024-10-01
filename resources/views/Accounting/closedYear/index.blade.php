@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Cierres Fiscales</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Cierre Fiscal</a></li>
				<li class="active-page"><a>Ver Cierres Fiscales</a></li>
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
						<div class="col-sm-6">Generar Cierre Fiscal</div>
					</div>
				</div>
				<div class="table-content">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<h4 class="text-center u-color-black">
								Esta a punto de iniciar el cierre Fiscal para cambio de año, recuerde que una vez realizado el proceso no hay vuelta atras, cualquier corrección tendra que realizarlo por asientos de ajuste.
							</h4>
							<h4 class="text-center u-color-black">
								Iniciaremos haciendo una serie de verificaciones, para estar seguros que todos los datos sean correctos.
							</h4>
							<h4 class="text-center u-color-black">
								Estaremos Haciendo el Cierre de {{changeLetterMonth($fiscal->month_first)}} - {{$fiscal->year}} a {{changeLetterMonth($fiscal->month_end)}} {{$fiscal->year}}
								<input type="hidden" id="year_fiscal" value="{{$closedYears->year}}">
								<input type="hidden" id="month_first" value="{{$fiscal->month_first}}">
								<input type="hidden" id="month_end" value="{{$fiscal->month_end}}">
							</h4>
						</div>
					</div>
				</div>
				<hr>
				<div class="row steps text-center">
					@if($error == null)
					<aside class"step1 u-mar-bot-1">
						<p>Paso 1: Verificación Usuario y Mes de Cierre</p>
						<a href="#" id="new_closed_year" class="btn btn-primary"><span class="fa fa-user"></span> Iniciar</a>
						<hr>
					</aside>
						@else
						<aside>
							<pre>{{$error}}</pre>
						</aside>
					@endif
					<aside class="step2 u-mar-bot-1 hide">
					</aside>
					<aside class="step3 u-mar-bot-1 hide">
					</aside>
					<aside class="step4 u-mar-bot-1 hide">
					</aside>
					<aside class="step5 u-mar-bot-1 hide">
					</aside>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection