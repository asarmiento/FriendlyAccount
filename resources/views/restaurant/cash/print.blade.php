<div class="print-invoice">
    <div class="print-invoice-container">
        <div class="header">
            <p>{{ userSchool()->name }}</p>
            <p>{{ userSchool()->carter }}</p>
            <p>{{ userSchool()->address }}</p>
            <p>Fech de Corte: {{ \Carbon\Carbon::parse($closingCash->created_at)->format('Y-m-d') }}</p>
            <p>Corte de Caja</p>
        </div>
        <div class="content">
            <hr/>
            @if(userSchool()->regime_type == 'tradicional')
                <div>
                    <span class='pull-left'>V. Gravadas</span>
                    <span class='pull-right'>{{number_format($sale,2)}}</span>
                </div>
                <div>
                    <span class='pull-left'>Imp. de Ventas</span>
                    <span class='pull-right'>{{number_format($iva,2)}}</span>
                </div>
            @else
                <div>
                    <span class='pull-left'>Total de Ventas</span>
                    <span class='pull-right'>{{number_format($sale,2)}}</span>
                </div>
            @endif
            <div>
                <span class='pull-left'>Imp. de Servicio</span>
                <span class='pull-right'>{{number_format($service,2)}}</span>
            </div>
            <div>
                <span class='pull-left bold'>T. de Ventas</span>
                <span class='pull-right bold'>{{number_format($totalSales,2)}}</span>
            </div>
            <div>
                <span class='pull-left '>T. pago a Prov.</span>
                <span class='pull-right '>{{number_format($payment_supplier,2)}}</span>
            </div>
            <div>
                <span class='pull-left bold'>T. Efectivo</span>
                <span class='pull-right bold'>{{number_format($total_sales,2)}}</span>
            </div>
        </div>
        <div style="clear: right;">
            <hr/>
            <div>Arqueo Físico</div>
            <table>
                <tr>
                    <td>Moneda</td>
                    <td>Cantidad</td>
                    <td>Total</td>
                </tr>
                <?php $tot = 0; ?>
                @foreach($currencies as $currencie)
                    <?php $tot += $currencie->total; ?>
                    <tr>
                        <td>{{$currencie->name}}</td>
                        <td>{{$currencie->amount}}</td>
                        <td>{{number_format($currencie->total,2,'.','')}}</td>
                    </tr>
                @endforeach
            </table>
            <p class="bold">Tot. Arqueo: {{number_format($tot,2,'.','')}}</p>
            <p class="bold">Balance:
                @if(isset($closingCash->missing))
                    -{{number_format($closingCash->missing,2,'.','')}}
                @else
                    {{number_format($closingCash->surplus,2,'.','')}}
                @endif
            </p>
        </div>
        <div  style="clear: right;">
            </hr>
            <div>Resumen de Facturas</div>
            <table>
                <tr>
                    <td>Fact</td>
                    <td>10% Serv.</td>
                    <td>Total</td>
                </tr>
                @foreach($invoices as $invoice)
                <tr>
                    <td>{{$invoice->numeration}}</td>
                    <td>{{number_format($invoice->service,0)}}</td>
                    <td>{{number_format($invoice->total,0)}}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="footer">
            <hr/>
            <p>Fecha de Impresión</p>
            <p>{{ \Carbon\Carbon::now()->format('Y-m-d h:i') }}</p>
        </div>
    </div>
</div>