@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
	<aside class="page"> 
		<h2>Control Alumnos Auxiliar</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Estudiantes</a></li>
				<li class="active-page"><a>Ver Control Alumnos Auxiliar</a></li>
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
							<h5><strong>Asientos de Control Alumnos Auxiliar</strong></h5>
						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">
						<table id="table_total_charges" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Fecha</th>
	                                <th>Referencia</th>
	                                <th>Debito</th>
	                                <th>Credito</th>
	                                <th>Balance</th>
	                            </tr>
	                        </thead>
	                        <tbody>
							@foreach($seats AS $seat)
	                            <tr>
	                            	<td class="text-center">{{$seat->date}}</td>
	                            	<td class="text-center">{{$seat->code}}</td>
									@if($seat->name =='debito')
									<?php $total += $seat->amount; ?>
	                            	<td class="text-center">{{$seat->amount}}</td>
	                            	<td class="text-center"></td>
									@else
                                        <?php $total -= $seat->amount; ?>
										<td class="text-center"></td>
	                            	<td class="text-center">{{$seat->amount}}</td>
									@endif
	                            	<td class="text-center">{{$total}}</td>
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