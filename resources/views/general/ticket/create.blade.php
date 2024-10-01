<!--
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/07/16
 * Time: 07:43 PM
 -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Creacion de Tikect</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Tikect</a></li>
                <li class="active-page"><a>Registrar Tikect</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <form id="datafiles" action="{{route('support-save')}}" method="post" enctype="multipart/form-data">
    <div class="paddingWrapper">
        <section class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="codeDegree">Titulo</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input name="codeDegree" class="form-control" type="text" maxlength="80">
                    </div>
                </div>
            </div>
            <div class="col-sm-7 col-md-7">
                <div class="form-mep">
                    <label for="nameDegree">Descripción del Problema o Sugerencia</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <textarea name="nameDegree" class="form-control"  rows="20"  maxlength="750">
                            </textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-mep">
                    <label for="statusDegree">Captura de pantalla o Foto</label>
                    <div class="row">
                        <input type="file" name="fileSupport"  >
                    </div>
                </div>
            </div>
        </section>
        <div class="row text-center">
            <button id="saveTicket">Grabar Ticket</button>
        </div>
    </div>
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection