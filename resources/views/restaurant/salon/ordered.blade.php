@extends('layouts.restaurant')

@section('styles')
    <style>
        .azul {
            background-color: #0000FF;
            color: #ffffff;
        }

        .rojo {
            background-color: #F2BDAC;
            color: #ffffff;
        }

        .verde {
            background-color: #B6D8B1;
            color: #000;
        }

        .morado {
            background-color: #803fd4;
            color: #ffffff;
        }

        .amarillo {
            background-color: #FFFF00;
            color: #000000;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/ladda-bootstrap/dist/ladda-themeless.min.css') }}">
@endsection

@section('content')
    <section class="row">
        <h2 class="text-center">Pedido para la Mesa: {{$table->name}}</h2>
        @foreach($groups as $group)
            <article class="col-sm-4">
                <aside class="panel panel-default">
                    <div class="panel-heading accordion-heading" data-toggle="collapse" data-parent="#accordion"
                         href="#{{$group->id}}">
                        <h3 class="panel-title text-center">
                            <strong>{{$group->name}}</strong>
                            <a style="font-size: .75em;" class="icon-collapse collapsed" aria-expanded="false"><span
                                        class="glyphicon glyphicon-chevron-down"></span></a>
                        </h3>
                    </div>
                    <div id="{{$group->id}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            @if($group->menus->count())
                                <table class="table-bordered table-menu" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Plato</th>
                                        <th class="text-center">Costo</th>
                                        <th class="text-center">Pedir</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($group->menus as $menu)
                                        <tr>
                                            <td>{{$menu->name}}</td>
                                            <td class="text-center">{{number_format(taxAdd($menu->costo),0)}}</td>
                                            <td class="text-center" style="padding: 0.5em;"><a
                                                        class="btn btn-info menu_restaurant" href="#"
                                                        data-token="{{$menu->token}}"
                                                        data-table="{{$table->token}}"><span
                                                            class="glyphicon glyphicon-ok"></span></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="row">
                                    No se han asignado platos a este grupo.
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>
            </article>
        @endforeach
    </section>
    <aside class="row">
        <div class="text-center">
            @if($foods)
                <a id="print_order_foods" class="btn btn-default azul" data-token="{{ $table->token }}">
                    <span class="fa fa-print"></span> Comanda Cocina</a>
            @endif

            <a href="{{route('ver-salon')}}" class="btn btn-info"><span class="fa fa-arrow-left"></span> Regresar</a>

            @if($drinks)
                <a id="print_order_drinks" class="btn btn-default morado" data-token="{{ $table->token }}">
                    <span class="fa fa-print"></span> Comanda Bebidas</a>
            @endif
        </div>
    </aside>
    <div id="modalMenuRestaurant" class="modal fade bs-example-modal-small" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-small">
            <div class="modal-content">

            </div>
        </div>
    </div>
    @if($orders->count())
        <section class="col-sm-12">
            <br>
            <table class="table table-bordered table-hover TableDetail">
                <thead>
                <tr>
                    <th></th>
                    <th>Cantidad</th>
                    <th>Menú</th>
                    <th>Anular</th>
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
                        <td>
                            @if($order->cooked == 0)
                                <button href="#" class="btn btn-danger btn-xs delete_order"
                                        data-token="{{$order->token}}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </td>
                        <td>{{$order->qty}}</td>
                        <td>{{$order->menuRestaurant->name}}
                            @if($order->modify)
                                <p class="font-2x">(sin:
                                    @foreach($order->modifyMenu as $key => $modify)
                                        @if($modify->type == 'Adicional')
                                            <?php $adicional = 'true'; continue; ?>
                                        @endif
                                        {{$modify->cookedProduct->name}}
                                        @if(count($order->modifyMenu) - 1 != $key)
                                            ,
                                        @else
                                            ).</p>
                            @endif
                            @endforeach
                            @if(isset($adicional))
                                <p class="font-2x">con:
                                    @endif
                                    @foreach($order->modifyMenu as $key => $modify)
                                        @if($modify->type == 'Base')
                                            <?php continue; ?>
                                        @endif
                                        {{$modify->cookedProduct->name}}
                                        @if(count($order->modifyMenu) - 1 != $key)
                                            ,
                                        @else
                                            ).</p>
                            @endif
                            @endforeach
                            @endif
                        </td>
                        <td>
                            @if($order->cooked == 1)
                                <button href="#" class="btn btn-danger btn-xs canceled_order"
                                        data-token="{{$order->token}}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{number_format(taxAdd($order->menuRestaurant->costo))}}</td>
                        <td>{{$order->menuRestaurant->money}}</td>
                        <td>{{\Carbon\Carbon::parse($order->created_at)}}</td>
                        <td>{{$order->user->nameComplete()}}</td>
                        <td>
                            @if($order->menuRestaurant->money == 'dolares')
                                {{number_format(taxAdd($order->menuRestaurant->costo) * $tc * $order->qty)}}
                                <?php $tot += $order->menuRestaurant->costo * $tc * $order->qty ?>
                            @else
                                {{number_format(taxAdd($order->menuRestaurant->costo) * $order->qty)}}
                                <?php $tot += $order->menuRestaurant->costo * $order->qty ?>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" align="right">Total</td>
                    <td>{{number_format(taxAdd($tot))}}</td>
                </tr>
                </tbody>
            </table>
        </section>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/spin.min.js') }}"></script>
    <script src="{{ asset('bower_components/ladda-bootstrap/dist/ladda.min.js') }}"></script>
@endsection