@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/restaurant.css')}}">
@endsection

@section('content')
    <div class="col-sm-3">
        <h5>
            <strong><a href="{{route('ver-salon')}}" class="btn btn-primary">Ir a Salon</a></strong>
        </h5>
    </div>

    <section class="row card-restaurant kitchen">
        @if($orders->count())
            @foreach($orders as $order)
                <article class="col-sm-4 padding-1">
                    <aside class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title text-center"><strong>{{convertTitle($order->tableSalon->name)}}</strong></h3>
                        </div>
                        <div class="panel-body">
                            <p>{{$order->total}} - {{$order->menuRestaurant->name}}</p>
                            <table class="table table-bordered table-hover TableDetail">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto Cocido</th>
                                        <th>Hora de Pedido</th>
                                        <th>Realizado Por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->menuRestaurant->cookedProduct as $key => $cookedProduct)
                                        <tr>
                                            <td>{{$cookedProduct->pivot->amount * $order->total}}</td>
                                            <td>{{$cookedProduct->name}}</td>
                                            <td>{{\Carbon\Carbon::parse($order->created_at)}}</td>
                                            <td>{{$order->user->nameComplete()}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a id="cooked" class="btn btn-success" data-token="{{$order->token}}">Cocinado</a>
                        </div>
                    </aside>
                </article>
            @endforeach
        @else
            <h2 class="text-center pending">No se tienen pedidos pendientes por atender.</h2>
        @endif
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/lib/socket.io.min.js') }}"></script>
    <script src="{{ asset('js/lib/handlebars.min.js') }}"></script>
    <script charset="UTF-8">
        var socket = io('http://192.168.10.10:3000');
        socket.on("kitchen-order-channel:AccountHon\\Events\\KitchenOrder", function(message){
            addOrderSalon(message);
        });
    </script>
@endsection