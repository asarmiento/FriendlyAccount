<div class="print-invoice">
	<div class="print-invoice-container">
		<div class="header">
			<p>{{ userSchool()->name }}</p>
			<p>{{ userSchool()->business_name }}</p>
			<p>Ced.: {{ userSchool()->charter.', '.userSchool()->address.', '.userSchool()->email }}</p>
			<p>
				FACTURA
				@if(isset($copy))
					({{$copy}})
				@endif:
				{{ $invoice->numeration}}
			</p>
			<p>FECHA: {{ \Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }} </p>
			@if($table->restaurant=='no')
				<p>CLIENTE: {{$invoice->client}}</p>
				<p>{{ $table->name }}</p>
			@else
				@if($table->restaurant == 'express')
					<p>CLIENTE: {{$invoice->client}}</p>
				@else
					<p>CLIENTE: {{$table->name}}</p>
				@endif
			@endif
			<p>Mesero: {{ Auth::user()->nameComplete() }}</p>
		</div>
		<div class="content">
			<hr>
			<div class='detail'>
				<span class='bold qty-print'>CANT</span>
				<span class='bold desc-print'>DESC</span>
				<span class='bold price-print'>P.U</span>
				<span class='bold total-print'>TOTAL</span>
			</div>
			<hr/>
			@foreach($lists as $list)
					<div class="orders">
						<span class="qty-print">{{ $list['cantidad'] }}</span>
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
					<div><span class='bold'>SUBTOTAL: </span>{{ number_format(($invoice->subtotal),0,'.','') }}</div>
				@else
					<div><span class='bold'>SUBTOTAL: </span>{{ number_format(taxAdd($invoice->subtotal),0,'.','') }}</div>
				@endif
				@if($invoice->percent_discount > 0)
					<div><span class='bold'>DESCUENTO: </span>{{ $invoice->percent_discount }}%</div>
					<div><span class='bold'>SUBTOTAL: </span>{{ $invoice->subtotal - $invoice->discount }}</div>
				@endif
				@if(userSchool()->regime_type == 'tradicional')
		        	<div><span class='bold'>I.V.A: </span>{{ $invoice->tax }}</div>
				@endif
		        <div><span class='bold'>10% SERVICIO: </span>{{ $invoice->service }}</div>
		        <div><span class='bold'>TOTAL: </span>{{ multipleOfFive($invoice->total) }}</div>
		        <div><span class='bold'>DOLARES: </span>{{ number_format($invoice->total/$invoice->tc,0,'.','') }}</div>
				<div>Su vuelto: {{ number_format($invoice->colones + ($invoice->dolares * $invoice->tc) - multipleOfFive($invoice->total) ,0,'.','') }}</div>
				<hr>
		        <div>Pagado con: {{$invoice->paymentMethod->name}}</div>
				<div>Factura Cancelada</div>
				@if($invoice->colones_t > 0)
		        	<div>
		        		₡ {{ $invoice->colones_t }} (tarjeta)
		        	</div>
		        @endif
		        @if($invoice->colones > 0)
					<div>
						₡ {{ $invoice->colones }}
					</div>
		        @endif
		        @if($invoice->dolares > 0)
					<div>
						$ {{ $invoice->dolares }}
					</div>
		        @endif
		        @if($invoice->missing > 0)
					<div>
						Faltante: ₡ {{ $invoice->missing }}
					</div>
		        @endif
		        {{--@if(in_array($invoice->payment_method_id, [5,8,9]))
					<div>
						N° de cuotas: {{ $invoice->dues }}
					</div>
		        @endif --}}
		        <hr/>
		        <div></div>
				@if(userSchool()->regime_type == 'tradicional')
		        	<div>Autorizado mediante oficio</div>
		        	<div>N° : 11-1997 de la D.G.T.D</div>
				@else
					<div>Regimen simplificado</div>
					<div>Autorizado mediante oficio</div>
					<div>N° : 11-1997 de la D.G.T.D</div>
				@endif
		        <div>GRACIAS POR SU COMPRA</div>
			</div>
		</div>
		<p></p>
		<p></p>

		<p style="height: 45px"><hr/></p>
	</div>
</div>