@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Modelos de Celulares</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Modelos de Celulares</a></li>
				<li class="active-page"><a>Registrar Modelos de Celulares</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="content-box-blue  col-md-12 col-sm-12">
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="customerCellphone" class="font-text-size">Clientes: {{$cellphones->customer->nameComplete()}} </label>
					<input type="hidden" id="customerCellphone" value="{{$cellphones->customer->token}}">
					<input type="hidden" id="technicalCellphone" value="{{$cellphones->token}}">
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="form-mep">
					<label for="serieCellphone" class="font-text-size">Autorizado: {{$cellphones->authorized}}</label>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="form-mep">
					<label for="passwordCellphone" class="font-text-size">Cedula de Autorizado: {{$cellphones->authorizedSign}}</label>
				</div>
			</div>
			</div>
			<div class="col-sm-12 col-md-12">
				<hr class="line-box-content">
			</div>
			<div class="content-box-blue col-md-12">
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">Equipo Recibido {{$cellphones->equipment}}</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="brandsCellphone" class="font-text-size">Marca de Equipo: {{$cellphones->brand->name}} {{$cellphones->modelWorkshop->name}}</label>
					</div>
				</div>
				<div class="col-sm-4 col-md-3">
					<div class="form-mep">
						<label for="colorCellphone" class="font-text-size">Color: {{$cellphones->color}}</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">S/N {{$cellphones->serie}}</label>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<hr class="line-box-content">
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">Otro tipo de Señales: {{$cellphones->otherType}}</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">Incluye Cargador: @if($cellphones->charger == 1) SI @else  NO @endif</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">S/N Cargador: {{$cellphones->chargerSeries}}</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-3 center">
					<div class="form-mep">
						<label for="serieCellphone" class="font-text-size">Estuche: @if($cellphones->case == 1) SI @else  NO @endif</label>
					</div>
				</div>
				<hr class="line-box-content">
				<div class="col-sm-3 col-md-3">
					<div class="form-mep">
						<label for="passwordCellphone" class="font-text-size">Contraseña/Pin del Telefono: {{$cellphones->password}}</label>
					</div>
				</div>
				<div class="col-sm-3 col-md-12">
					<div class="form-mep">
						<label for="descriptionCellphone" class="font-text-size">Otras Señas Fisicas: </label>
						<p align=”center”  style="text-align: justify; width: 40% ">{{$cellphones->physicalSigns}}</p>
					</div>
				</div>
				<div class="col-sm-3 col-md-12">
					<div class="form-mep">
						<label for="physicalSignsCellphone" class="font-text-size">Solicitud Adicionales: </label>
						<p>{{$cellphones->additionalRequests}}</p>
					</div>
				</div>
				<div class="col-sm-3 col-md-12">
					<div class="form-mep">
						<label for="descriptionCellphone" class="font-text-size">Problema Reportado: </label>
						<p>{{$cellphones->reportedProblems}}</p>

					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="dateOfReceiptCellphone"  class="font-text-size">Fecha de Recibido: {{$cellphones->date_of_receipt}}</label>
					</div>
				</div>
			</div>
			<div class="content-box-gray">
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="diagnosisCellphone">Diagnóstico: </label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-signal"></i></span>
							<textarea rows="5" id="diagnosisCellphone" @if($technical->count()>0) readonly style="background-color: #d8ecf7; color: #000"  @endif cols="55">@if($technical->count()>0) {{$technical->get()[0]->diagnosis}} @endif</textarea>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="workDoneCellphone">Trabajo Realizado:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-money"></i></span>
							<textarea id="workDoneCellphone" @if($technical->count()>0) readonly style="background-color: #d8ecf7; color: #000" @endif rows="5" cols="55">@if($technical->count()>0) {{$technical->get()[0]->work_done}} @endif</textarea>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="answerUsedCellphone">Respuesto Utilizados:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<textarea rows="5" cols="55" @if($technical->count()>0) readonly style="background-color: #d8ecf7; color: #000" @endif id="answerUsedCellphone">@if($technical->count()>0) {{$technical->get()[0]->answer_used}} @endif</textarea>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="recommendationsCellphone">Recomendaciones:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
							<textarea rows="5"  cols="55" @if($technical->count()>0) readonly style="background-color: #d8ecf7; color: #000" @endif id="recommendationsCellphone">@if($technical->count()>0) {{$technical->get()[0]->recommendations}} @endif</textarea>

						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="deliveredCellphone">Fecha Entrega</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
							@if($technical->count()>0)
								<input type="date" id="deliveredCellphone"  readonly style="background-color: #d8ecf7; color: #000"  value="{{$technical->get()[0]->delivered}}"  >
							@else
								<input type="date" id="deliveredCellphone"  value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"  >
							@endif
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="finalCostCellphone">Costo Final</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
							@if($technical->count()>0)
							<input type="number" readonly  id="finalCostCellphone" style="background-color: #d8ecf7; color: #000"  value="{{$technical->get()[0]->final_cost}}"  placeholder="0.00" >
							@else
							<input type="number"   id="finalCostCellphone"   placeholder="0.00" >
							@endif
						</div>
					</div>
				</div>
			</div>
		</section>
		<div class="row text-center">
			<a href="{{route('ver-taller-de-celulares')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="technicalCellphone" data-url="taller-de-celulares" class="btn btn-success">Grabar Modelo</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/pages/workshops/cellphone.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection