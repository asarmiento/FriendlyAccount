@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Lista de Equipos </h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Equipos en Reparaciones</a></li>
				<li class="active-page"><a>Ver Equipos en Reparaciones</a></li>
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
							<h5><strong>Lista de Equipos en Reparaciones</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-taller-de-celulares')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_cellphone" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Cliente</th>
	                                <th>Marca de Telefono</th>
	                                <th>Daño</th>
	                                <th>Prioridad</th>
	                                <th>Recibido</th>
	                                <th>Fecha Entrega</th>
	                                <th>Serie</th>
	                                <th>Tecnico</th>
	                                <th>Ficha</th>
	                                <th>Editar</th>
                               </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($cellphones as $cellphone)
		                            <tr>
		                                <td class="text-center " data-token="{{$cellphone->token}}">
											{{ convertTitle($cellphone->customer->nameComplete()) }}</td>
										<td class="text-center ">
											{{ convertTitle($cellphone->brand->name) }}</td>
		                                <td class="text-center ">{{ ($cellphone->damage) }}</td>
		                                <td class="text-center ">{{$cellphone->priority}}</td>
		                                <td class="text-center ">{{ $cellphone->date_of_receipt }}</td>
		                                <td class="text-center ">{{ $cellphone->date_of_delivery	 }}</td>
		                                <td class="text-center ">{{ $cellphone->serie }}</td>
										<td class="text-center edit-row">
											<a href="{{route('tecnico-taller-de-celulares', $cellphone->token)}}">
												<i class="fa fa-search"></i>
											</a>
										</td>
										<td class="text-center edit-row">
											<a class="danger" href="{{route('ficha-taller-de-celulares', $cellphone->token)}}"><i class="fa fa-file-pdf-o"></i></a>
										</td>
		                                <td class="text-center edit-row">
											<a href="{{route('editar-taller-de-celulares', $cellphone->token)}}"><i class="fa fa-pencil-square-o"></i></a>
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