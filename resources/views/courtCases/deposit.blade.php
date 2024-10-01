<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 20/07/16
 * Time: 06:22 PM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Cortes de Caja</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Cortes de Caja</a></li>
                <li class="active-page"><a>Registrar de Depositos pendientes</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="accoutingPeriodAuxiliarySeat">Periodo Contable</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input  id="accoutingPeriodAuxiliarySeat" class="form-control" value="{{period()}}" type="text" disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="dateAuxiliarySeat">Fecha del Corte</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input id="dateAuxiliarySeat" class="form-control" type="text" value="{{dateShort()}}" disabled>
                    </div>
                </div>
            </div>
        </section>
        <section>
            @if( count($auxiliaryReceipts) > 0 || count($receipts) > 0 )
            <div id="depositCourtCase">
                    <div class="panel-body text-center" id="createDeposit">

                        <h1>Total en Efectivo: {{number_format($totalCashes,2)}}</h1>
                        <div class="col-lg-3 col-md-3">
                            <label>Cuenta Bancaria:
                            <select id="bankDeposits" class="form-control">
                                @foreach($banks AS $bank)
                                    <option value="{{$bank->token}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                                </label>
                         </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Referencia Deposito:
                            <input type="number" id="numberDeposits" class="form-control">
                                </label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Monto Deposito:
                            <input type="decimal" id="amountDeposits" class="form-control">
                                </label>

                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Fecha Deposito:
                            <input type="date" id="dateDeposits" class="form-control">
                            </label>
                        </div>

                        <div class="col-lg-2 col-md-2">
                            <label>Numero Recibo:
                            <select id="codeReceiptDeposit" class="form-control">
                                @foreach($receiptsAll AS $receipt)
                                    <option value="{{$receipt}}">{{$receipt}}</option>
                                @endforeach
                            </select>
                            </label>
                        </div>
                        @if( $totalCashes > 0 )
                        <div class="panel-footer  col-lg-15 col-md-15 text-center">
                            <button id="saveDepositCourtCase" data-url="cortes-de-caja" class="btn btn-primary">
                                <span class="fa fa-plus" aria-hidden="true"></span> Agregar</button>
                        </div>
                        @endif
                    </div>
                    <div class="row table table-data text-center">
                    <table >
                        <thead class="">
                            <tr>
                                <th width="300" class="text-center">Cuenta Bancaria</th>
                                <th width="110" class="text-center">Fecha</th>
                                <th width="120" class="text-center">Referencia</th>
                                <th width="110" class="text-center">Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                    <tbody  >
                        @foreach($depositsAcc AS $deposit)
                        <tr>
                            <td width="300">{{$deposit->catalog->name}}</td>
                            <td width="110">{{$deposit->date}}</td>
                            <td width="120">{{$deposit->number}}</td>
                            <td width="110">{{$deposit->amount}}</td>
                            @if($deposit->type == 'court')
                                <td ><button data-url="cortes-de-caja" data-token="{{$deposit->token}}"  class="btn btn-danger deleteDeposit"><span  class="fa fa-minus" aria-hidden="true"></span> Eliminar</button></td>
                            @endif
                        </tr>
                        @endforeach
                        @foreach($depositsAux AS $deposit)
                            <tr>
                                <td width="300">{{$deposit->catalog->name}}</td>
                                <td width="110">{{$deposit->date}}</td>
                                <td width="120">{{$deposit->number}}</td>
                                <td width="110">{{$deposit->amount}}</td>
                                @if($deposit->type == 'court')
                                <td ><button data-url="cortes-de-caja" data-token="{{$deposit->token}}" class="btn btn-danger deleteDeposit"><span  class="fa fa-minus" aria-hidden="true"></span> Eliminar</button></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
            @else
                <div class="col-sm-12 col-md-12 text-center">
                    <h3>Aún no se han registrados Recibos.</h3>
                </div>
            @endif
        </section>
        <div class="row text-center">
           
                <a href="{{route('crear-cortes-de-caja')}}" id="DepositCourtCase"  class="btn btn-success">Aplicar Depositos Corte de Caja</a>
           
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/caja/deposit.js') }}"></script>
@endsection
