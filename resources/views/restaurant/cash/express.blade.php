@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <style>
        .cash-express{
            min-height: 87vh;
        }
    </style>
@endsection

@section('content')
    <section class="row card-restaurant">
        <div class="col-xs-12">
            <a href="{{ url('/institucion/inst/salon') }}" class="btn btn-default">Restaurante</a>
        </div>
        <div id="app">
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/express.js') }}"></script>
@endsection