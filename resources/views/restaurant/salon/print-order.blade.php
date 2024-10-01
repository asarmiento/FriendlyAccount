<div class="print-invoice">
	<div class="print-invoice-container">
		<div class="header">
			<p>{{ userSchool()->name }}</p>
			<p>{{ userSchool()->business_name }}</p>
			<p>ORDEN DE PEDIDO</p>
			<p>FECHA: {{ \Carbon\Carbon::now()->format('d-m-Y') }} </p>
			@if($table->restaurant=='no')
				<p>CLIENTE: Cliente Contado</p>
				<p>{{ $table->name }}</p>
			@else
				<p>CLIENTE: {{$table->name}}</p>
			@endif
			<p>Mesero: {{ Auth::user()->nameComplete() }}</p>
		</div>
		<div class="content">
			<hr/>
			<div class='detail'>
				<span class='bold qty-print'>CANT</span>
				<span class='bold desc-print'>DESC</span>
				<span class='bold price-print'>P.U</span>
				<span class='bold total-print'>TOTAL</span>
			</div>
			<hr/>
			@foreach($lists as $list)
				<div class="orders">
					<span class="qty-print">{{$list['cantidad'] }}</span>
					<span class="desc-print">{{fixed($list['menu'], 15)}}</span>
					@if(userSchool()->regime_type == 'tradicional')
						@if($list['money'] == 'colones')
							<span class="price-print">{{ number_format(($list['costo']),0,'.','') }}</span>
							<span class="price-print">{{ number_format($list['cantidad'] * ($list['costo']),0,'.','') }}</span>
						@else
							<span class="price-print">
								{{ number_format(($list['costo']),0) }}
							</span>
							<span class="price-print">
								{{ number_format(($list['costo']) * $list['cantidad'],0,'.','') }}
							</span>
						@endif
					@else
						@if($list['money'] == 'colones')
							<span class="price-print">{{ number_format(taxAdd($list['costo']),0,'.','') }}</span>
							<span class="price-print">{{ number_format($list['cantidad'] * taxAdd($list['costo']),0,'.','') }}</span>
						@else
							<span class="price-print">
								{{ number_format(taxAdd($list['costo']),0) }}
							</span>
							<span class="price-print">
								{{ number_format(taxAdd($list['costo']) * $list['cantidad'],0,'.','') }}
							</span>
						@endif
					@endif
				</div>
			@endforeach
			<hr/>
			<div class="footer">
				@if(userSchool()->regime_type == 'tradicional')
					<div><span class='bold'>SUBTOTAL: </span>{{ number_format(($total_orders['subtotal']),0,'.','') }}</div>
		        	<div><span class='bold'>I.V.A: </span>{{ $total_orders['tax'] }}</div>
		        @else
					<div><span class='bold'>SUBTOTAL: </span>{{ number_format(taxAdd($total_orders['subtotal']),0,'.','') }}</div>
				@endif
					<div><span class='bold'>10% SERVICIO: </span>{{ $total_orders['service'] }}</div>
		        <div><span class='bold'>TOTAL: </span>{{ multipleOfFive($total_orders['total'])  }}</div>
		        <div><span class='bold'>DOLARES: </span>{{ number_format(($total_orders['dolar']),0,'.','')  }}</div>
		        <hr/>
			</div>
		</div>
	</div>
</div>