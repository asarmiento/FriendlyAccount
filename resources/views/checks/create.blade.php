@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Cheques</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Asientos</a></li>
                <li class="active-page"><a>Registrar Cheques</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="accoutingPeriodCheck">Periodo Contable</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input  id="accoutingPeriodCheck" class="form-control" value="{{period()}}" data-token="{{periodSchool()->token}}" type="text" disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="dateCheck">Fecha del Cheque</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input id="dateCheck" class="form-control datepicker" type="text" value="{{dateShort()}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="typeSeatCheck">Número del Cheque</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input id="codeCheck" class="form-control" value="{{$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity}}" type="text" data-token="{{$typeSeat[0]->token}}" disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="detailCheck">Paguese a la Orden de:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input id="paguesenCheck" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="accountCheck">Cuenta</label>
                    <select id="accountCheck" class="form-control" data-type="select">
                        @if($catalogBanks->isEmpty())
                            <option value="">No existen cuentas contables</option>
                        @endif
                        @foreach($catalogBanks as $catalogBank)
                            <option value="{{$catalogBank->token}}">{{ $catalogBank->code.' '.convertTitle($catalogBank->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="accountCheck">Registro</label>
                    <input type="text" readonly class="form-control" data-token="{{ ($types->token) }}" value="{{ convertTitle($types->name) }}">
                </div>
            </div>
            <div class="col-sm-8 col-md-8">
                <div class="form-mep">
                    <label>Cuenta de Contraparte - Monto - Descripción</label>
                    <div class="row accountPartCheckContainer">
                        <aside class="row" style="margin-bottom: .5em;">
                            <div class="col-sm-6" style="padding:0;">
                                <select class="form-control accountPartCheck" data-type="select">
                                    @if($catalogs->isEmpty())
                                        <option value="">No existen cuentas contables</option>
                                    @else
                                        @foreach($catalogs as $catalog)
                                            <option value="{{$catalog->token}}">{{ $catalog->code.' '.convertTitle($catalog->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-2" style="padding-right:0;">
                                <input class="form-control amountCheck" type="number" step="0.01">
                            </div>
                            <div class="col-sm-4" style="padding-right:0;">
                                <input class="form-control detailCheck" type="text">
                            </div>
                        </aside>
                    </div>
                    <button id="addPartCheck" class="btn btn-info" style="margin-top:.5em;">Agregar Cuenta</button>
                    <button id="removePartCheck" class="btn btn-danger hide" style="margin-top:.5em;">Eliminar Cuenta</button>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="totalCheck">Total del Cheques</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                        <input id="totalCheck" class="form-control" type="number" min="0.00" value="0.00" disabled>
                    </div>
                </div>
            </div>
        </section>
        <div id="btn-Check" class="row text-center">
            <a href="{{route('ver-cheques')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
            <a id="saveCheck" href="#" data-url="cheques" class="btn btn-success">
                <i class="fa fa-floppy-o"></i> Grabar Cheques
            </a>
            <a id="anularCheck" href="#" data-url="cheques" class="btn btn-danger">
                <i class="fa fa-gavel"></i> Anular Cheque
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
@endsection