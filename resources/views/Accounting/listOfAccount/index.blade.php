@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Lista de Cuentas Master</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Lista de Cuentas Master</a></li>
				<li class="active-page"><a>Ver Lista de Cuentas Master</a></li>
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
							<h5><strong>Lista de Cuentas Master</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-cuentas')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_listOfAccount" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Nombre</th>
	                                <th>Rubro</th>
	                                <th>Tipo</th>
	                                <th>Cuenta Madre</th>
	                                <th>Tipo de Negocio</th>
	                                <th>Editar</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($listOfAccounts as $listOfAccount)
		                            <tr>
		                                <td class="text-center degree_code" data-token="{{$listOfAccount->token}}">{{ convertTitle($listOfAccount->name) }}</td>
		                                <td class="text-center degree_code" >{{ convertTitle($listOfAccount->style) }}</td>
		                                <td class="text-center degree_code" >{{ convertTitle($listOfAccount->type) }}</td>
		                                <td class="text-center degree_code" >{{ convertTitle($listOfAccount->listOfAccount->name) }}</td>
		                                <td class="text-center degree_code" >{{ convertTitle($listOfAccount->typeOfCompany->name) }}</td>
		                                <td class="text-center edit-row">

											<a href="{{route('editar-cuentas', $listOfAccount->token)}}"><i class="fa fa-pencil-square-o"></i></a>
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
	<script src="{{ asset('js/pages/general/typeOfCompany.js') }}"></script>
	<script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection