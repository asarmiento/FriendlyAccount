@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
@endsection

@section('page')
    <aside class="page">
        <h2>Error</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')

   <div class="alert alert-danger"><i class="fa fa-dashcube"></i> {{ $exception->getMessage() }} <i class="fa fa-dashcube"></i></div>

@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
@endsection