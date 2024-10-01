<div class="print-receipt">
	<div class="print-receipt-container">
		<div class="print-receipt-title bold">{{mb_strtoupper(userSchool()->name, 'UTF-8')}}</div>
		<div class="print-receipt-header row">
			<div class="address pull-left">
				<p class="u-mar-0">{{userSchool()->town}}</p>
				<p class="u-mar-0">TEL: {{userSchool()->phoneOne}}</p>
				<p class="u-mar-0">{{userSchool()->email}}</p>
			</div>
			<div class="receipt pull-right">
				<div class="name">RECIBO</div>
				<div class="number">
					<span class="pull-left">N°</span>
					<span class="pull-right">{{$dataReceipt[0]->receipt_number}}</span>
				</div>
			</div>
		</div>
		<div class="print-receipt-info row">
			<div class="client u-left-40">
				<div class="content">
					<p class="u-mar-0">Cliente:</p>
					<p class="u-mar-0">{{$dataReceipt[0]->received_from}}</p>
				</div>
			</div>
			<div class="user u-left-60">
				<div class="content">
					<div class="row">
						<div class="date u-left-50">
							<p class="u-mar-0">Lugar y Fecha de expedición:</p>
							<p class="u-mar-0">{{userSchool()->twon}}</p>
							<p class="u-mar-0">{{$date}}</p>
						</div>
						<div class="expiration u-left-50">
							<p>Vencimiento</p>
							<p>{{$date}}</p>
						</div>
					</div>
					<div class="row">
						<div class="employee u-left-50">
							<p>Vendedor: {{Auth::user()->name}}</p>
						</div>
						<div class="method u-left-50">
							<p>Condiciones: Efectivo</p>
						</div>
					</div>
					<div class="row">
						<div class="referred u-left-50">
							<p>Refer.:</p>
						</div>
						<div class="send u-left-50">
							<p>Envío: Entrega</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="print-receipt-table">
			<table style="width: 100%">
				<tr>
					<th>Código</th>
					<th width="300">Descripción</th>
					<th>Cantidad</th>
					<th>Precio Unit.</th>
					<th>Subtotal</th>
				</tr>
				@foreach($dataReceipt as $receipt)
				<tr>
					<td>{{$receipt->catalogs->code}}</td>
					<td>{{$receipt->catalogs->name}}</td>
					<td class="u-text-center">1</td>
					<td class="u-text-center">{{$receipt->amount}}</td>
					<td class="u-text-center">{{$receipt->amount}}</td>
				</tr>
				@endforeach
				<tr class="subtotal">
					<td colspan="3"></td>
					<td class="text-center">Subtotal:</td>
					<td class="text-center">{{$totalReceipt}}</td>
				</tr>
			</table>
		</div>
		<div class="print-receipt-concept">
			{{$dataReceipt[0]->detail}}
		</div>
		<div class="print-receipt-total pull-right">
			<div class="row">
				<span class="pull-left">Total</span>
				<span class="pull-right">{{$totalReceipt}}</span>
			</div>
		</div>
	</div>
</div>