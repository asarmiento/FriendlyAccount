@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Auxiliares Proveedores</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Asientos Auxiliares</a></li>
				<li class="active-page"><a>Ver Auxiliares Proveedores</a></li>
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
							<h5><strong>Lista de movimientos Auxiliares de Proveedores</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-auxiliares-proveedor')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_auxiliary_supplier" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                            	<th>Fecha</th>
	                                <th>Código</th>
	                                <th>Detalle</th>
	                                <th>Monto</th>
	                                <th>Proveedor</th>
	                                <th>Periodo Contable</th>
	                                <th>Tipo</th>
                                    <th>Reporte</th>
                                    <th>Edición</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($auxiliarySuppliers as $auxiliarySupplier)
		                            <tr>
		                            	<td class="text-center auxiliary_seat_date" data-token="">{{ $auxiliarySupplier->date }}</td>
		                                <td class="text-center">{{ convertTitle($auxiliarySupplier->code) }}</td>
		                                <td class="text-center">{{ convertTitle($auxiliarySupplier->detail) }}</td>
		                                <td class="text-center">{{ $auxiliarySupplier->amount }}</td>
		                                <td class="text-center">{{ $auxiliarySupplier->supplier->charter.' - '.convertTitle($auxiliarySupplier->supplier->nameComplete()) }}</td>
		                                <td class="text-center">{{ $auxiliarySupplier->accountingPeriods->period() }}</td>
		                                <td class="text-center">{{ convertTitle($auxiliarySupplier->types->name) }}</td>
                                        <td class="text-center edit-row">
                                            <a target="_blank" href="{{route('ver-asientos-auxiliary', $auxiliarySupplier->token)}}"><i class="fa fa-file-excel-o"></i></a>
                                        </td>
                                        <td class="text-center edit-row">
											<a href="{{route('proveedores-auxiliares', $auxiliarySupplier->token)}}"><i class="fa fa-eye"></i></a>
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
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
@endsection