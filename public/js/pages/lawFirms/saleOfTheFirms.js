$(function() {
    var data = {};
    /**
     * Created by anwarsarmiento on 6/12/16.
     */
    /**
     * Facturas de Ventas
     */

    //Save TypeSeat
    $(document).off('click', '#saveInvoiceBufete');
    $(document).on('click', '#saveInvoiceBufete', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/save/all';
        data.descriptionSaleOfTheFirm = $('#descriptionSaleOfTheFirm').val();
        data.amountSaleOfTheFirm = $('#amountSaleOfTheFirm').val();
        data.dateSaleOfTheFirm = $('#dateSaleOfTheFirm').val();
        data.numerationSaleOfTheFirm = $('#numerationSaleOfTheFirm').val();
        data.statusSaleOfTheFirm = $('#statusSaleOfTheFirm').bootstrapSwitch('state');
        data.paymentSaleOfTheFirm = $('#paymentSaleOfTheFirm').val();
        data.descriptionReceiptSaleOfTheFirm = $('#descriptionReceiptSaleOfTheFirm').val();
        data.customerSaleOfTheFirm = $('#customerSaleOfTheFirm').val();
        data.charterOptionSaleOfTheFirm = $('#charterOptionSaleOfTheFirm').val();
        data.fnameOptionSaleOfTheFirm = $('#fnameOptionSaleOfTheFirm').val();
        data.flastOptionSaleOfTheFirm = $('#flastOptionSaleOfTheFirm').val();
        data.phoneOptionSaleOfTheFirm = $('#phoneOptionSaleOfTheFirm').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
               if(data.success){
                     $.unblockUI();
                    window.open('/institucion/inst/'+data.message);
                    location.reload();
               }else{
                   messageAjax(data);
              }
            });
    });

    $('input[name="status-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {

       var status = $('#statusSaleOfTheFirm').bootstrapSwitch('state');
        if(status==false) {
            $('#montoPayment').addClass('show').removeClass('hide');
            $('#descriptPayment').addClass('show').removeClass('hide');
        }else{
            $('#montoPayment').addClass('hide').removeClass('show');
            $('#descriptPayment').addClass('hide').removeClass('show');
        }
    });

    // Vue js
    var edit  = '<tr>'
        edit += '<td></td>'
        edit += '<td style="padding-left: 5px"><textarea id="descriptionSaleOfTheFirm" rows="5" cols="60" maxlength="500"></textarea></td>';
        edit += '<td style="padding-left: 15px"><input type="number" id="amountSaleOfTheFirm" class="form-control"></td>';
        edit += '<td style="padding-left: 15px"><a id="lineInvoice" data-url="factura-bufete" href="#" class="btn btn-success"><span class="fa fa-plus"></span></a></td>'
        edit += '</tr>';

    var tpl  = '<tr>';
        tpl += '<td><a class="btn btn-danger"><span class="fa fa-remove"></span></a></td>';
        tpl += '<td style="padding-left: 5px"></td>';
        tpl += '<td style="padding-left: 15px"></td>';
        tpl += '<td style="padding-left: 15px"></td>';
        tpl += '</tr>';
});