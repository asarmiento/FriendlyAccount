@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Compras</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Compras</a></li>
				<li class="active-page"><a>Registrar Compras</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row" id="form-buy">
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="accoutingPeriodBuy">Periodo Contable</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
						<input  id="accoutingPeriodBuy" class="form-control" value="{{period()}}" data-token="{{periodSchool()->token}}" type="text" disabled>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="dateBuy">Fecha de Emisión de la Compra</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input id="dateBuy" class="form-control datepicker" type="text" value="{{dateShort()}}">
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="typeSeatBuy">Número de la Compra</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
						@if($typeSeat->isEmpty())
							<input class="form-control" type="text" value="Debe Crear el tipo de Compra">
						@endif
						@if(!$typeSeat->isEmpty())
						<input id="typeSeatBuy" class="form-control" value="{{$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity}}" type="text" data-token="{{$typeSeat[0]->token}}" disabled>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="supplierBuy">Proveedor</label>
					<select id="supplierBuy" class="form-control select2" data-type="select">
						@foreach($suppliers as $supplier)
							<option value="{{$supplier->token}}">{{ $supplier->identification.' '.convertTitle($supplier->name) }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="typeBuy">Condición de Compra</label>
					<div class="row" style="margin-top:.25em;">
						<label class="radio-inline"><input type="radio" name="typeBuy" checked value="0">Efectivo</label>
						<label class="radio-inline"><input type="radio" name="typeBuy"  value="1">Transferencia</label>
						<label class="radio-inline"><input type="radio" name="typeBuy" value="2">Crédito</label>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4 hide cuentaTransferencia">
				<div class="form-mep">
					<label for="dateExpirationBuy">Cuenta Bancaria Transferencia</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<select id="transfBuy" class="form-control" data-type="select">
							@foreach($catalogs as $catalog)
								<option value="{{$catalog->token}}">{{ $catalog->code.' '.convertTitle($catalog->name) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4 hide dateExpiration">
				<div class="form-mep">
					<label for="dateExpirationBuy">Fecha de Vencimiento</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input id="dateExpirationBuy" class="form-control datepicker" type="text" value="{{dateShort()}}">
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="referenceBuy">Factura de referencia</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
						<input id="referenceBuy" class="form-control" type="text" />
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="typeInvoice">Tipo de Factura</label>
					<div class="row" style="margin-top:.25em;">
						<label class="radio-inline"><input type="radio" name="typeInvoice" checked value="0">Detallada</label>
						<label class="radio-inline"><input type="radio" name="typeInvoice" value="1">I.V.I</label>
						<label class="radio-inline"><input type="radio" name="typeInvoice" value="2">I.V.A</label>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="totalGravadoBuy">Total Gravado</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="totalGravadoBuy" class="form-control notIvi" type="number" min="0.00" step="0.01" value="0.00" />
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="totalExcentoBuy">Total Excento</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="totalExcentoBuy" class="form-control notIvi" type="number" min="0.00" step="0.01"  value="0.00"/>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="ivaBuy">Total I.V.A</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="ivaBuy" class="form-control notIvi" type="number" min="0.00" step="0.01"  value="0.00"/>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="otherBuy">Total Otros</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="otherBuy" class="form-control notIvi" type="number" min="0.00" step="0.01"  value="0.00"/>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="discountBuy">Total Descuentos</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="discountBuy" class="form-control" type="number" min="0.00" step="0.01"  value="0.00"/>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="subsidizedBuy">Total Bonificado</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="subsidizedBuy" class="form-control" type="number" min="0.00" step="0.01"  value="0.00"/>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="totalBuy">Total de Compra</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-usd"></i></span>
						<input id="totalBuy" class="form-control" type="number" min="0.00" step="0.01" value="0.00" disabled>
					</div>
				</div>
			</div>
		</section>
		<div id="btn-Check" class="row text-center">
			<a href="{{route('ver-compras')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a id="saveBuy" href="#" data-url="compras" class="btn btn-success">
				<i class="fa fa-floppy-o"></i> Grabar Compra
			</a>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
	<script src="{{ asset('bower_components/matchHeight/jquery.matchHeight-min.js') }}"></script>
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
	<script src="{{ asset('js/lib/i18n/es.js') }}"></script>
@endsection