<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 13/07/16
 * Time: 12:08 AM
-->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Reimprimir Asientos</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Asientos</a></li>
                <li class="active-page"><a>Reimprimir  Asientos</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="accoutingPeriodSeating">Asiento Inicial</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <select id="tokenSeatingReprintInitial"  class="select2 select2-dropdown form-control">
                        @foreach($seatings AS $seating)
                            <option value="{{$seating->code}}">{{$seating->code}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-mep">
                    <label for="accoutingPeriodSeating">Asiento Final</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <select id="tokenSeatingReprintFinish"  class="select2 select2-dropdown form-control">
                        @foreach($seatings AS $seating)
                            <option value="{{$seating->code}}">{{$seating->code}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6">
                <div class="form-mep">

                    <a id="reimprimirSeating" href="#" class="btn btn-success" data-url="asientos" style="margin-top:.5em;">
                        <i class="fa fa-floppy-o"></i> Descargar
                    </a>
                </div>
            </div>

        </section>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lib/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection