<div class="row">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        {{--<h4 class="modal-title" id="mySmallModalLabel">Recibo - {{$invoice->numeration}}</h4> --}}
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Descuento</th>
                    <th>Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $table->name }}</td>
                    <td>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</td>
                    <td><span>₡</span><span class="total_invoice"> {{ $total_orders['total'] }}</span></td>
                    <td><span class="discount">0</span>%</td>
                    <td><span>₡</span><span class="total_invoice_discount"> {{ $total_orders['total'] }}</span></td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <form action="{{ route('cash-invoice') }}" method="post" id="form-closed">
                <input type="hidden" id="exchange" value="{{$exchange}}">
                <input type="hidden" name="table" value="{{$table->token}}">
                <input type="hidden" id="total_invoice_calc" name="total_invoice_calc">
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="card">Cédula</label>
                        <input class="form-control" type="number" name="card" id="card"  placeholder="">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="pay">A Nombre de</label>
                        <input class="form-control" type="text" name="client" id="client" value="{{client()}}" placeholder="">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="pay">Email</label>
                        <input class="form-control" type="text" name="email" id="email"  placeholder="">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="discount">Descuento (%)</label>
                        <input id="discount" class="form-control" type="number" name="discount" min="0" value="0">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="pass">Contraseña</label>
                        <input id="pass" class="form-control" type="password" name="pass">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="paymentMethod">Forma de Pago</label>
                        <select id="paymentMethod" name="paymentMethod" class="form-control">
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{$paymentMethod->id}}">{{ convertTitle($paymentMethod->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 hide dues">
                    <div class="form-mep">
                        <label for="dues">Cantidad de Cuotas</label>
                        <input id="dues" class="form-control" min="1" type="number" name="dues" value="1">
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 pay_t hide">
                    <div class="form-mep">
                        <label for="pay_t">Paga Con Tarjeta (₡)</label>
                        <input id="pay_t" class="form-control" min="1" type="number" step="0.01" name="pay_t" >
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 pay">
                    <div class="form-mep">
                        <label for="pay">Paga Con (₡)</label>
                        <input id="pay" class="form-control" min="1" type="number" step="0.01" name="pay" value="0" >
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 hide usd">
                    <div class="form-mep">
                        <label for="usd">Paga Con ($)</label>
                        <input id="usd" class="form-control" min="1" type="number" step="0.01" name="usd" >
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 change">
                    <div class="form-mep">
                        <label for="change">Vuelto en Colones</label>
                        <div class="input-group">
                            <span class="input-group-addon">₡</span>
                            <input id="change" class="form-control" type="text" value="-{{$total_orders['total']}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 hide change_usd">
                    <div class="form-mep">
                        <label for="change_usd">Vuelto en Dólares</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            <input id="change_usd" class="form-control" type="text" value="-{{number_format(($total_orders['total'] / $exchange)+0.01,2)}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-mep">
                        <label for="missing">Faltante</label>
                        <input id="missing" class="form-control" type="number" name="missing">
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 text-center">
                    <div class="form-mep">
                        <div>Factura electrónico si ?: <input type="checkbox" id="fe" name="fe" /></div>
                        <a href="{{ URL::route('unir-cuentas', $table->token) }}" class="btn btn-default"><i class="fa fa-users"></i> Unir Cuentas</a>
                        <a id="submit-cash" class="btn btn-success ladda-button" data-style="expand-left"><span class="ladda-label"><i class="fa fa-floppy-o"></i> Registrar</a>
                        <a href="{{ URL::route('dividir-cuentas', $table->token) }}" class="btn btn-default"><i class="fa fa-users"></i> Dividir Cuentas</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 text-center">
                    <div class="form-mep">
                        <div class="msg"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
