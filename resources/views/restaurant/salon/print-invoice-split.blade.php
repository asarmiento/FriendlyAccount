@foreach($invoices_print as $invoice)
	<div class="print-invoice">
		<div class="print-invoice-container">
			<div class="header">
				<p>{{ userSchool()->name }}</p>
				<p>{{ userSchool()->charter }}</p>
				<p>{{ userSchool()->address }}</p>
				<p>FACTURA: {{ $invoice->numeration}}</p>
				<p>FECHA: {{ \Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }} </p>
				<p>A NOMBRE DE: {{$invoice->client}}</p>
				<p>{{ $table->name }}</p>
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
				@foreach($invoice->orders as $order)
					<div class="orders">
						<span class="qty-print">{{ $order->qty }}</span>
						<span class="desc-print">{{fixed($order->menuRestaurant->name, 15)}}</span>
						@if($order->menuRestaurant->money == 'colones')
							<span class="price-print">{{ $order->menuRestaurant->costo }}</span>
							<span class="price-print">{{ $order->qty * $order->menuRestaurant->costo }}</span>
						@else
							<span class="price-print">
								{{ $order->menuRestaurant->costo * $tc }}
							</span>
							<span class="price-print">
								{{ $order->menuRestaurant->costo * $tc * $order->qty }}
							</span>
						@endif
					</div>
				@endforeach
				<hr/>
				<div class="footer">
					<div><span class='bold'>SUBTOTAL: </span>{{ $invoice->subtotal }}</div>
					@if($invoice->percent_discount > 0)
						<div><span class='bold'>DESCUENTO: </span>{{ $invoice->percent_discount }}%</div>
						<div><span class='bold'>SUBTOTAL: </span>{{ $invoice->subtotal - $invoice->discount }}</div>
					@endif
			        <div><span class='bold'>I.V.A: </span>{{ $invoice->tax }}</div>
			        <div><span class='bold'>I.S.V: </span>{{ $invoice->service }}</div>
			        <div><span class='bold'>TOTAL: </span>{{ $invoice->total }}</div>
			        <hr>
			        <div>Factura Cancelada</div>
			        <div>Pagado con: {{$invoice->paymentMethod->name}}</div>
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
			        {{--@if(in_array($invoice->payment_method_id, [5,8,9]))
						<div>
							N° de cuotas: {{ $invoice->dues }}
						</div>
			        @endif --}}
			        <hr/>
			        <div class="footer-leyenda">GRAVADO</div>
			        <div class="footer-leyenda">Autorizado mediante oficio</div>
			        <div class="footer-leyenda">N° : 11-1997 de la D.G.T.D</div>
			        <div class="footer-leyenda">GRACIAS POR SU COMPRA</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<hr/>
@endforeach