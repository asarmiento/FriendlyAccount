@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Factura</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Bufete</a></li>
				<li class="active-page"><a>Facturación</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="nameDegree">Cliente</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<select id="customerSaleOfTheFirm" class="form-control select2">
							<option value="">Seleccione un Cliente</option>
							@foreach($customers AS $customer)
								<option value="{{$customer->token}}">{{$customer->nameComplete()}}</option>
							@endforeach
						</select>
						<input id="charterOptionSaleOfTheFirm" placeholder="Cedula" size="20" class="form-control" type="text" >
						<input id="fnameOptionSaleOfTheFirm" placeholder="Primer Nombre"  size="20" class="form-control" type="text" >
						<input id="flastOptionSaleOfTheFirm" placeholder="Primer Apellido"  size="20" class="form-control" type="text" >
						<input id="phoneOptionSaleOfTheFirm" placeholder="Telefono o Celular"  size="20" class="form-control" type="text" >
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="form-mep">
					<label for="codeDegree">N° de Factura</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
				      	<input id="numerationSaleOfTheFirm" readonly class="form-control" type="text" value="{{$number}}" >
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="form-mep">
					<label for="dateSaleOfTheFirm">Fecha</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input id="dateSaleOfTheFirm" readonly class="form-control" type="text" value="{{dateShort()}}">
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="form-mep">
					<label for="statusDegree">Forma de Pago</label>
					<div class="row">
						<input id="statusSaleOfTheFirm"   type="checkbox" name="status-checkbox" data-on-text="Contado" data-off-text="Crédito" data-on-color="info" data-off-color="warning" data-label-text="Activado" checked>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div id="montoPayment" class="form-mep hide" >
					<label for="statusDegree">Abono a Factura de Credito:</label>
					<div class="row">
						<input id="paymentSaleOfTheFirm"  class="form-control" type="number" placeholder="0.00" >
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div  id="descriptPayment" class="form-mep hide">
					<label for="statusDegree">Descripcion para recibo:</label>
					<div class="row">
						<input id="descriptionReceiptSaleOfTheFirm"  class="form-control" type="text" placeholder="Descripción de Recibos" >
					</div>
				</div>
			</div>

		</section>
    		<br>
		<div id="example" class="row text-center" style="background: #EEE">
			<table-bufete :sales='{!! json_encode($sales->toArray()) !!}'></table-bufete>
		</div>
		<div class="row text-center">
			<a href="{{route('ver-factura-bufete')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="saveInvoiceBufete" data-url="factura-bufete" class="btn btn-success">Grabar Factura</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
	<script src="{{ asset('js/pages/lawFirms/saleOfTheFirms.js') }}"></script>
	<script src="{{ asset('js/example.js') }}"></script>
@endsection