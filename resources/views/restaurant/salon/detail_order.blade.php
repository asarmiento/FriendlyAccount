<div class="print-invoice">
    <div class="print-invoice-container">
        <div class="header">
            Pedido {{ $table->name }}
        </div>
        <div class="content">
            <hr/>
            <div>
                <p>Mesero: {{ Auth::user()->nameComplete() }}</p>
                <p>Hora: {{ \Carbon\Carbon::now() }}</p>
            </div>
            <hr/>
            @foreach($orders as $order)
                <div class="products">
                    <span class="font-2x">{{ $order->qty }}</span>
                    <span class="font-2x">{{ $order->menuRestaurant->name }}</span>
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
                </div>
            @endforeach
            <br>
            <hr>
        </div>
    </div>
</div>