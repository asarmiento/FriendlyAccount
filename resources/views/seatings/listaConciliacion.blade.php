@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">

@endsection

@section('page')
	<aside class="page"> 
		<h2>Lista para Conciliar</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Lista para Conciliar</a></li>
				<li class="active-page"><a>Ver Lista para Conciliar</a></li>
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
							<h5><strong>Lista de Asientos</strong></h5>
						</div>
						<div class="col-sm-6">

						</div>
					</div>
				</div>
				<div class="table-content">
					<div class="table-responsive">

						<div class="col-md-6">

							<h3>Lista de Contabilidad</h3>
							<table>
								<thead>
								<tr>
							<th class="col-lg-2">Asiento</th>
							<th colspan="3">Descripción</th>
							<th class="col-lg-2">Fecha</th>
							<th class="col-lg-2">Debito</th>
							<th class="col-lg-2">Credito</th>
								</tr>
								</thead>
                                <?php $debito=0; $credito=0; ?>
								<tbody>
							@foreach($accounting AS $account)

								<tr>
								<td class="col-lg-2">{{$account->code}}</td>
								<td colspan="3">{{$account->detail}}</td>
								<td class="col-lg-2">{{$account->date}}</td>
									@if($account->type_id == 6)
										<td class="col-lg-2">{{number_format($account->amount,2)}}</td>
										<td class="col-lg-2">{{number_format(0,2)}}</td>
                                        <?php $debito += $account->amount; ?>
									@else
										<td class="col-lg-2">{{number_format(0,2)}}</td>
										<td class="col-lg-2">{{number_format($account->amount,2)}}</td>
                                        <?php $credito += $account->amount; ?>
									@endif
								</tr>
							@endforeach
							@foreach($accountingPart AS $account)
								<tr>
									<td class="col-lg-2">{{$account->code}}</td>
									<td colspan="3">{{$account->detail}}</td>
									<td class="col-lg-2">{{$account->date}}</td>
									@if($account->type_id == 6)
										<td class="col-lg-2">{{number_format($account->amount,2)}}</td>
										<td class="col-lg-2">{{number_format(0,2)}}</td>
                                        <?php $debito += $account->amount; ?>
									@else
										<td class="col-lg-2">{{number_format(0,2)}}</td>
										<td class="col-lg-2">{{number_format($account->amount,2)}}</td>
                                        <?php $credito += $account->amount; ?>
									@endif

								</tr>
							@endforeach
								<tr>
									<td colspan="5">Total</td>
									<td>{{number_format($debito,2)}}</td>
									<td>{{number_format($credito,2)}}</td>
								</tr>
								<tr>
									<td colspan="5">Saldo Contabilidad</td>
									<td>{{number_format($credito-$debito,2)}}</td>

								</tr>
							<tr>
								<td colspan="5">Saldo Acumulado Contabilidad</td>
								<td>{{number_format($balanceAccount,2)}}</td>

							</tr>
								</tbody>

							</table>
						</div>
						<div class="col-md-6">
							<h3>Lista de Auxiliar</h3>
							<table>
								<thead>
								<tr>
									<th class="col-lg-2">Asiento</th>
									<th colspan="3">Descripción</th>
									<th class="col-lg-2">Fecha</th>
									<th class="col-lg-2">Debito</th>
									<th class="col-lg-2">Credito</th>
								</tr>
								</thead>
								<tbody>
								<?php $debito=0; $credito=0; ?>
								@foreach($tableAuxiliar AS $aux)

									<tr>
										<td class="col-lg-2">{{$aux->code}}</td>
										<td colspan="3">{{$aux->detail}}</td>
										<td class="col-lg-2">{{$aux->date}}</td>
										@if($aux->type_id == 6)
											<td class="col-lg-2">{{number_format(0,2)}}</td>
											<td class="col-lg-2">{{number_format($aux->amount,2)}}</td>
                                            <?php $credito += $aux->amount; ?>
										@else
											<td class="col-lg-2">{{number_format($aux->amount,2)}}</td>
											<td class="col-lg-2">{{number_format(0,2)}}</td>
                                            <?php  $debito  +=$aux->amount; ?>
										@endif
										</tr>

								@endforeach
								<tr>
									<td colspan="5">Total</td>
									<td>{{number_format($debito,2)}}</td>
									<td>{{number_format($credito,2)}}</td>
								</tr>
								<tr>
									<td colspan="5">Saldo Auxiliar</td>
									<td>{{number_format($credito-$debito,2)}}</td>

								</tr>
								<tr>
									<td colspan="5">Saldo Acumulado Auxiliar</td>
									<td>{{number_format($balanceAux,2)}}</td>

								</tr>
								</tbody>
							</table>
						</div>

						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/lib/select2.min.js') }}"></script>
@endsection
