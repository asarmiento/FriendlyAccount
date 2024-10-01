@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Empleados</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Empleados</a></li>
				<li class="active-page"><a>Ver Empleados</a></li>
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
							<h5><strong>Lista de Empleados</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-employess')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_task" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>N°</th>
	                                <th>Cedula</th>
	                                <th>Nombre Completo</th>
	                                <th>Email</th>
	                                <th>Telefono</th>
	                                <th>Edición</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($employess as $key =>$employes)
		                            <tr>
		                                <td class="text-center task_number">{{$key+1}}</td>
		                                <td class="text-center task_number">{{$employes->charter}}</td>
		                                <td class="text-center task_name">{{convertTitle($employes->nameComplete())}}</td>
		                                <td class="text-center">{{$employes->email}}</td>
		                                <td class="text-center">{{$employes->phone}}</td>
		                                <td class="text-center edit-row">
	                                		@if($employes->deleted_at)
	                                			<a id="activeTask" data-url="empleado" href="#">
	                                				<i class="fa fa-check-square-o"></i>
                                				</a>
	                                		@else
	                                			<a id="deleteTask" data-url="empleado" href="#">
													<i class="fa fa-trash-o"></i>
												</a>
	                                		@endif
											<a href="{{route('editar-employess', $employes->token)}}"><i class="fa fa-pencil-square-o"></i></a>
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