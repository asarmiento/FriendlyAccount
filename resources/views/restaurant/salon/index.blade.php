@extends('layouts.restaurant')

@section('styles')
    <style>
        .azul{background-color: #90BBF6;
            color: #000;}


        .rojo{ background-color: #F2BDAC;
            color: #ffffff;}

        .verde{ background-color: #B6D8B1;
            color: #000;}

        .morado{background-color: #BA84FF;
            color: #ffffff;}


        .amarillo{background-color: #F9F9BD;
            color: #000000;}

    </style>
    <link rel="stylesheet" href="{{ asset('bower_components/ladda-bootstrap/dist/ladda-themeless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endsection

@section('content')
    <section class="row card-restaurant">
        <div class="row container">
            <a href="{{route('cash.express')}}" class="btn btn-default">Caja Rápida</a>
            {{-- Cajero --}}
            @if(currentUser()->type_user_id == 4 || currentUser()->type_user_id == 1 )
                <a href="{{route('cierre-de-caja')}}" class="btn btn-info">$ Cierre de Caja</a>
                <a href="{{route('ver-cierre-de-caja')}}" class="btn btn-info">Lista de Cierres de Caja</a>
            @endif
            <a href="{{route('change-password')}}" class="btn btn-default">Cambio Contraseña</a>
            <a href="{{route('ver-facturas')}}" class="btn btn-primary">Ver Facturas</a>
            <a href="{{route('payment-caja')}}" class="btn btn-primary">Pago a Proveedores</a>
            <a href="{{route('crear-mesas-Restauran')}}" class="btn btn-default">Crear Cuentas (Mesas)</a>
        </div>
        @if($allTables->count())
            @if($allTables->count() >= 5 && $allTables->count() <= 12)
                <article class="col-sm-4 padding-1">
                    @elseif($allTables->count() >= 13 && $allTables->count() <= 24)
                        <article class="col-sm-3 padding-1">
                            @elseif($allTables->count() >= 25 )
                                <article class="col-sm-2 padding-1">
                                    @else
                                        <article class="col-sm-4 padding-1">
                                            @endif
               <aside class="panel panel-default">
                   <form method="post" action="{{route('print-restaurant')}}" target="_blank">
                  <div class="panel-heading clearfix">
                        <h3 class="panel-title text-center"><strong>Servicio Restaurante</strong></h3>
                  </div>
                    <div class="panel-body ">
                        <div class="text-center  ">{{csrf_field()}}
                            <div class="col-md-6 form-group "><label>Cédula</label><input type="number" placeholder="Cédula Cliente" name="cardRest"></div>
                            <div class="col-md-6 form-group ">  <label>Nombre</label><input type="text" placeholder="Nombre Cliente" name="customerRest"></div>
                            <div class="col-md-6 form-group ">  <label>Email</label><input type="email" placeholder="Email Cliente" name="emailRest"></div>
                            <div class="col-md-6 form-group "> <label>Monto</label><input type="number" placeholder="0" name="amountRest"></div>
                             <input  class="btn btn-default" value="PDF" type="submit">
                        </div>
                    </div>
                   </form>
                </aside>
            </article>
            @foreach($allTables as $table)
                @if($allTables->count() >= 5 && $allTables->count() <= 12)
                    <article class="col-sm-4 padding-1">
                @elseif($allTables->count() >= 13 && $allTables->count() <= 24)
                    <article class="col-sm-3 padding-1">
                @elseif($allTables->count() >= 25 )
                    <article class="col-sm-2 padding-1">
                @else
                   <article class="col-sm-4 padding-1">
                @endif

                    <aside class="panel panel-default">
                        @if($table->status() == 'order')
                        <div class="panel-heading clearfix bg-success">
                            <h3 class="panel-title text-center color-white"><strong>{{convertTitle($table->name)}}</strong></h3>
                        @elseif($table->status() == 'cash')
                        <div class="panel-heading clearfix bg-info">
                            <h3 class="panel-title text-center color-white"><strong>{{convertTitle($table->name)}}</strong></h3>
                        @else
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title text-center"><strong>{{convertTitle($table->name)}}</strong></h3>
                        @endif
                        </div>
                        <div class="panel-body <?php echo $table->color; ?>">
                            <div class="text-center ">
                                <a href="{{route('salon-token', $table->token)}}" class="btn btn-lg btn-default"><span class="fa fa-cutlery"></span></a>
                                {{-- <a id="info" class="btn btn-lg btn-default" data-token="{{$table->token}}"><span class="fa fa-info-circle"></span></a> --}}
                                @if(Auth::user()->type_user_id == 4 || Auth::user()->type_user_id == 1)
                                    <a id="print" class="btn btn-lg btn-default" data-token="{{$table->token}}"><span class="fa fa-print"></span></a>

                                    <a id="cash" class="btn btn-lg btn-default" data-token="{{$table->token}}"><span>$</span></a>
                                @endif
                            </div>
                        </div>
                    </aside>
                </article>
            @endforeach
        @else
            <h2 class="text-center">No se ha asignado ninguna mesa a esta institución.</h2>
        @endif
    </section>
    <div id="modalOrders" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/lib/handlebars.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/spin.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/ladda.min.js') }}"></script>
    <script src="{{ asset('js/pages/salon.js') }}"></script>
@endsection
