@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Proveedores</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Proveedores</a></li>
				<li class="active-page"><a>Ver Proveedores</a></li>
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
							<h5><strong>Lista de Proveedores</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-proveedores')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_supplier" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                {{-- <th>Codigo</th> --}}
	                                <th>Cedula</th>
	                                <th>Nombre de Proveedor</th>
	                                <th>Telefono</th>
	                                <th>Limite Credito</th>
	                                <th>Total Credito</th>
	                                <th>Total Comprado</th>
	                                <th>Estado Cuenta</th>
	                                <th>Marcas</th>
	                                <th>Edición</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        @if( count($suppliers->allFilterScholl()) > 0 )
	                        	@foreach($suppliers->allFilterScholl() as $supplier)
		                            <tr>
		                                {{-- <td class="text-center ">{{ convertTitle($supplier->code) }}</td> --}}
		                                <td class="text-center ">{{ convertTitle($supplier->charter) }}</td>
		                                <td class="text-center ">{{ convertTitle($supplier->name) }}</td>
		                                <td class="text-center ">{{ convertTitle($supplier->phone) }}</td>
		                                <td class="text-center">{{ number_format($supplier->amount) }}</td>
		                                <td class="text-center">{{ number_format($auxiliarSuppliers->totalDuoBuySupplier($supplier->id,'type','Credito')) }}</td>
                                        <td class="text-center">{{ number_format($auxiliarSuppliers->totalBuySupplier($supplier->id)) }}</td>
                                        <td class="text-center edit-row">
                                            <a target="_blank" href="{{route('ver-estado-de-cuenta-proveedor', $supplier->token)}}"><i class="fa fa-file-excel-o"></i></a>
                                        </td>
                                        <td class="text-center">
											<a href="{{route('ver-proveedores-marcas', $supplier->token)}}"><i class="fa fa-eye"></i></a>
		                                </td>
		                                <td class="text-center">
											<a href="{{route('editar-proveedor', $supplier->token)}}"><i class="fa fa-pencil-square-o"></i></a>
		                                </td>
		                            </tr>
	                            @endforeach
                           	@endif
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