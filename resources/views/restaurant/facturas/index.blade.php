@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
	<link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Facturas Registradas</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Facturas Registradas</a></li>
				<li class="active-page"><a>Ver Facturas Registradas</a></li>
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
							<h5><strong>Lista de Facturas Registradas</strong></h5>
						</div>
						<div class="col-sm-3">
							<h5>
								<strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
							</h5>
						</div>
						<div class="col-sm-3">
							<h5>
								<strong>Total de Propinas {{\Carbon\Carbon::now()->format('Y-m-d')}}: {{$total_service}}</strong>
							</h5>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_invoice" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>N° Factura</th>
	                                <th>Fecha</th>
	                                <th>Subtotal Excento</th>
	                                <th>Subtotal Gravado</th>
	                                <th>Subtotal</th>
	                                <th>Impuesto</th>
	                                <th>Propinas</th>
	                                <th>Total</th>
	                                <th>Anular</th>
	                                <th>Reimpresión</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($invoices as $invoice)
		                            <tr>
		                                <td class="text-center degree_code" data-token="{{$invoice->token}}">{{ convertTitle($invoice->numeration) }}</td>
										<td class="text-center degree_name">{{ $invoice->date }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->subtotal_exempt) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->subtotal_taxed) }}</td>
										<td class="text-center degree_name">{{ number_format($invoice->subtotal) }}</td>
		                                <td class="text-center degree_name">{{ number_format($invoice->tax) }}</td>
		                                <td class="text-center degree_name">{{ number_format($invoice->service) }}</td>
		                                <td class="text-center degree_name">{{ multipleOfFive($invoice->total) }}</td>
										@if($invoice->status =='activo' && $invoice->court == 0)
		                                 <td class="text-center">

		                                	<a style="font-size: 1.25em;"  href="{{route('anular-facturas',$invoice->token)}}"><i class="fa fa-eraser" aria-hidden="true"></i></a>

		                                </td>
											@else
											<td></td>
										@endif
										<td class="text-center">
											@if($invoice->status == 'activo')
											<a href="#" data-token="{{$invoice->token}}" id="re-print"><span class="fa fa-print"></span></a>
											@endif
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