@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page">
		@if(userSchool()->type == "taller")
			<h2>Modelos de Vehiculos</h2>
		@else
			<h2>Modelos de Celulares</h2>
		@endif
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				@if(userSchool()->type == "taller")
					<li><a>Modelos de Vehiculos</a></li>
					<li class="active-page"><a>Ver Modelos de Vehiculos</a></li>
				@else
					<li><a>Modelos de Celulares</a></li>
					<li class="active-page"><a>Ver Modelos de Celulares</a></li>
				@endif
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
							@if(userSchool()->type == "taller")
								<h5><strong>Lista de Modelos de Vehiculos</strong></h5>
							@else
								<h5><strong>Lista de Modelos de Celulares</strong></h5>
							@endif
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-modelo-de-vehiculo')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_model_Of_The_Vehicle" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
									@if(userSchool()->type == "taller")
										<th>Marca de Vehiculo</th>
										<th>Modelo</th>
										<th>Cantidad de vehiculos</th>
										<th>Editar</th>
									@else
										<th>Marca de Celular</th>
										<th>Modelo</th>
										<th>Cantidad de Celular</th>
										<th>Editar</th>
									@endif

                               </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($modelOfTheVehicles as $modelOfTheVehicle)
		                            <tr>
		                                <td class="text-center " data-token="{{$modelOfTheVehicle->token}}">{{ convertTitle($modelOfTheVehicle->brand->name) }}</td>
		                                <td class="text-center ">{{ convertTitle($modelOfTheVehicle->name) }}</td>
		                                <td class="text-center "></td>
		                                <td class="text-center edit-row">
											<a href="{{route('editar-modelo-de-vehiculo', $modelOfTheVehicle->token)}}"><i class="fa fa-pencil-square-o"></i></a>
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