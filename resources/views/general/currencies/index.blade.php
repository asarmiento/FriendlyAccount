@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Denominacion Monedas</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Monedas</a></li>
				<li class="active-page"><a>Ver Denominación de Monedas</a></li>
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
							<h5><strong>Lista de Denominación de Monedas</strong></h5>
						</div>
						<div class="col-sm-6">
							<a href="{{route('crear-monedas')}}" class="btn btn-info pull-right">
								<span class="glyphicon glyphicon-plus"></span>
								<span>Nuevo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_degree" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Nombre</th>
	                                <th>Valor</th>
	                                <th>Editar</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($currencies as $currency)
		                            <tr>
		                                <td class="text-center degree_code" data-token="{{$currency->token}}">{{ convertTitle($currency->name) }}</td>
		                                <td class="text-center degree_name">{{ number_format($currency->value) }}</td>
		                                <td class="text-center edit-row">

											<a href="{{route('editar-currencies', $currency->id)}}"><i class="fa fa-pencil-square-o"></i></a>
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