@extends('layouts.restaurant')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('content')
    <section class="row ">
        <form id="" method="post" action="{{route('post-unir-cuentas')}}"  >
            <div class="fondo-title" style="
            background-color: #155C9F;
            color: #ffffff;
            text-align: center;
            height: 100px;">
            <h2 style=" padding: 30px" id="">Mesa que Pagara la Cuenta - {{$table->name}}</h2>
            <input id="idTableMaster"  type="hidden" value="{{$table->token}}">
            </div>
            <div class="modal-body">
            {{csrf_field()}}
            <table class="table table-bordered table-hover TableDetail">
                <thead>
                    <tr>
                        <th class="text-center">Mesas Activas</th>
                        <th class="text-center">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tablesActive as $order)
                        @if($order->id == $table->id)

                        @else
                        <tr>
                            <td>{{$order->name}}</td>
                            <td>
                                <input type="checkbox"  name="tableActive" data-token="{{$order->token}}"  class="tableActive form-control"></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="row">

                    <div class="form-group">
                        <button type="submit"  id="saveJoinOrders" data-url="unir-cuentas" class="btn btn-info"><i class="fa fa-users"></i> Unir Cuentas</button>
                        <a href="{{ route('ver-salon') }}"  class="btn btn-default"><span class="fa fa-arrow-left"></span> Regresar</a>
                    </div>
            </div>
        </form>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/pages/restaurant/join_orders.js') }}"></script>
@endsection