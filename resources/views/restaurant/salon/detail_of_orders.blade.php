<div class="row">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
        <h4 class="modal-title" id="mySmallModalLabel">Detalle de la - {{$table->name}}</h4> 
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
                        <td>{{number_format(taxAdd($order->menuRestaurant->costo),0)}}</td>
                        <td>{{($order->menuRestaurant->money)}}</td>
                        <td>{{\Carbon\Carbon::parse($order->created_at)}}</td>
                        <td>{{$order->user->nameComplete()}}</td>
                        <td>
                            @if($order->menuRestaurant->money == 'dolares')
                                {{number_format(taxAdd($order->menuRestaurant->costo ) * $order->total,0)}}
                                <?php $tot += taxAdd($order->menuRestaurant->costo ) * $order->total ?>
                            @else
                                {{number_format(taxAdd($order->menuRestaurant->costo )* $order->total,0)}}
                                <?php $tot += taxAdd($order->menuRestaurant->costo) * $order->total ?>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" align="right">Subtotal</td>
                    <td>{{number_format($tot,0)}}</td>
                </tr>
            </tbody>
        </table>
        @if(isset($client))
            <form class="row" id="printOrder" style="margin-bottom: 1em">
                {{--<div class="col-sm-6">
                    <input class="form-control" type="text" name="client" id="client" value="{{$client}}" placeholder="">
                </div>--}}
                <div class="col-sm-12 text-center">
                    <a class="btn btn-success" data-token="{{$table->token}}"><span class="fa fa-print"></span> Imprimir Orden</a>
                </div>
            </form>
        @endif
        @else
            <p class="text-center">No se han realizado pedidos a esta mesa.</p>
        @endif
    </div>
</div>