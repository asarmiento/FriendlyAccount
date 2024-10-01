@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page">
		<h2>Facturas Realizadas</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Facturas Realizadas</a></li>
				<li class="active-page"><a>Ver Facturas Realizadas</a></li>
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
							<h5><strong>Lista de Facturas Realizadas</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-factura-bufete')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo Factura</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_SaleOfTheFirm" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>N Factura</th>
	                                <th>Cliente</th>
	                                <th>Description</th>
	                                <th>Fecha</th>
	                                <th>Monto</th>
	                                <th>Estado</th>
	                                <th>Pdf</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($saleOfTheFirms as $saleOfTheFirm)
		                            <tr>
		                                <td class="text-center degree_code" data-token="{{$saleOfTheFirm->token}}">{{ ($saleOfTheFirm->number) }}</td>
		                                <td class="text-center degree_name">{{ convertTitle($saleOfTheFirm->customer->nameComplete()) }}</td>
		                                <td class="text-center degree_name">{{ convertTitle($saleOfTheFirm->description) }}</td>
		                                <td class="text-center degree_name">{{ convertTitle($saleOfTheFirm->invoice->date) }}</td>
		                                <td class="text-center degree_name">{{ number_format($saleOfTheFirm->invoice->total) }}</td>
		                                <td class="text-center">
		                                	@if($saleOfTheFirm->deleted_at)
												<span>Inactivo</span>
		                                	@else
												<span>Activo</span>
		                                	@endif
		                                </td>
		                                <td class="text-center edit-row">
	                                		<a href="{{route('pdf-factura-bufete', $saleOfTheFirm->invoice->token)}}" target="_blank" class=""><i class="fa fa-file-pdf-o fa-2x red"></i></a>
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
	<script>
		$(function() {
			dataTable('#table_SaleOfTheFirm', 'Facturas');
		});
	</script>
@endsection