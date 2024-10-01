@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
	<link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Cortes de Caja</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Cortes de Caja</a></li>
				<li class="active-page"><a>Ver Cortes de Caja</a></li>
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
						<div class="col-sm-2">
							<h5><strong>Lista de Cortes de Caja</strong></h5>
						</div>
						<div class="col-sm-7">
							<form action="{{route('reporte-cierre-de-caja')}}" method="get" target="_blank">
								<div class="col-sm-3">Fecha Inicio<input name="dateI" type="date"></div>
								<div class="col-sm-3">Fecha Final<input name="dateF" type="date"></div>
								<div class="col-sm-4"><input type="submit" class="btn btn-success" value="Buscar"></div>
							</form>
						</div>
						<div class="col-sm-2">
							<h5>
								<strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
							</h5>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_invoice" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Fecha de Corte</th>
	                                <th>M. Exento</th>
	                                <th>M. Gravado</th>
	                                <th>IVA</th>
	                                <th>Imp. Servicio</th>
	                                <th>Total</th>
	                                <th>Reimpresión</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($ClosedCash as $invoice)
		                            <tr>
		                                <td class="text-center degree_code" data-token="{{$invoice->token}}">{{ convertTitle($invoice->created_at) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->exempt_sales) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->taxed_sales) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->tax_sales) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->service_sales) }}</td>
		                                <td class="text-center degree_name">
											{{ number_format($invoice->total_sales) }}</td>
		                                <td class="text-center">
											<a href="#" data-token="{{$invoice->token}}" id="re-print"><span class="fa fa-print"></span></a>
										</td>
		                            </tr>
	                            @endforeach
	                        </tbody>
	                    </table>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection