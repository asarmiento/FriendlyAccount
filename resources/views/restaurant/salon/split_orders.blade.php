@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endsection

@section('content')
    <section class="row card-restaurant">
        <div class="row container">
            <h4 class="modal-title" id="mySmallModalLabel">Orden Original de la - {{$table->name}}</h4> 
        </div>
    <div class="modal-body">
        @if($orders->count())
            <table class="table table-bordered table-hover TableDetail">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Menú</th>
                        <th>Costo Unitario</th>
                        <th>Moneda</th>
                        <th>Hora de Pedido</th>
                        <th>Realizado Por</th>
                        <th>Total ₡</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $tot = 0 ?>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$order->total}}</td>
                            <td>{{$order->menuRestaurant->name}}</td>
                            <td>{{taxAdd($order->menuRestaurant->costo)}}</td>
                            <td>{{$order->menuRestaurant->money}}</td>
                            <td>{{\Carbon\Carbon::parse($order->created_at)}}</td>
                            <td>{{$order->user->nameComplete()}}</td>
                            <td>
                                @if($order->menuRestaurant->money == 'dolares')
                                    {{taxAdd($order->menuRestaurant->costo) * $order->total}}
                                    <?php $tot += taxAdd($order->menuRestaurant->costo ) * $order->total ?>
                                @else
                                    {{taxAdd($order->menuRestaurant->costo) * $order->total}}
                                    <?php $tot += taxAdd($order->menuRestaurant->costo) * $order->total ?>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" align="right">Subtotal</td>
                        <td>{{$tot}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <form id="form_split" class="form-inline">
                    <div class="form-group">
                        <label for="clients">Digite la cantidad de personas que se van a dividir la orden:</label>
                        <input type="number" class="form-control" id="clients" min="2" value="2">
                        <button type="submit" class="btn btn-info"><i class="fa fa-users"></i> Asignar clientes</button>
                        <a href="{{ route('ver-salon') }}" class="btn btn-default"><span class="fa fa-arrow-left"></span> Regresar</a>
                    </div>
                </form>
            </div>
            <div id="split_orders_detail">

            </div>
        @else
            <p class="text-center">No se han realizado pedidos a esta mesa.</p>
            <div class="text-center">
                <a href="{{ route('ver-salon') }}" class="btn btn-default"><span class="fa fa-arrow-left"></span> Regresar</a>
            </div>
        @endif
    </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/lib/ractive.js') }}"></script>
    <script>
        var orders          = {!! json_encode($orders) !!};
        var orders_ori      = {!! json_encode($orders) !!};
        var payments_method = {!! json_encode($paymentMethods) !!}
        var exchange        = {!! json_encode($tc) !!}
        var table           = {!! json_encode($table) !!}
        var iva             = {!! json_encode(iva()) !!}
        var isv             = {!! json_encode(isv()) !!}
    </script>
    <script src="{{ asset('js/pages/split_orders.js') }}"></script>
@endsection