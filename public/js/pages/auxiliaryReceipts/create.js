$(function() {
    var data = {};
    /**
     * Auxiliary Receipt
     */
    // Save Detail Auxiliary Receipt
    $(document).off('click', '#saveDetailAuxiliaryReceipt');
    $(document).on('click', '#saveDetailAuxiliaryReceipt', function(e){
        e.preventDefault();
        var url;
        url = $(this).data('url');
        url = url + '/save';
        data.accoutingPeriodAuxiliaryReceipt = $('#accoutingPeriodAuxiliaryReceipt').val();
        data.dateAuxiliaryReceipt            = $('#dateAuxiliaryReceipt').val();
        data.receiptNumberAuxiliaryReceipt   = $('#receiptNumberAuxiliaryReceipt').val();
        data.receivedFromAuxiliaryReceipt    = $('#receivedFromAuxiliaryReceipt').val();
        data.detailAuxiliaryReceipt          = $('#detailAuxiliaryReceipt').val();
        data.amountAuxiliaryReceipt          = $('#amountAuxiliaryReceipt').val();
        data.financialRecordAuxiliaryReceipt = $('#financialRecordAuxiliaryReceipt').val();
        data.codeStudent                     = $("#financialRecordAuxiliaryReceipt option:selected").text();
        ajaxForm(url,'post',data, null, 'true')
        .done( function (response) {
            if(response.success){
                $.unblockUI();
                var tr = addItemRow(data, response, 'receipt');
                if($('#table_auxiliar_receipt_temp tbody tr:first').exists()){
                    $('#table_auxiliar_receipt_temp tbody tr:first').before(tr);
                    $('#totalAuxiliaryReceipt').val(response.message.total);
                }else{
                    $('#table_auxiliar_receipt_temp').removeClass('hide');
                    $('#table_auxiliar_receipt_temp tbody').append(tr);
                    $('#saveAuxiliaryReceipt').removeClass('hide');
                    if(!$("#tokenAuxiliaryReceipt").exists()){
                        var token = '<input id="tokenAuxiliaryReceipt" type="hidden" value="'+ response.message.token +'">';
                        $("#table_auxiliar_receipt_temp tbody").prepend(token);
                    }
                    $('#totalAuxiliaryReceipt').val(response.message.total);
                }
            }else{
                messageErrorAjax(response);
            }
        });
    });

    //Delete Auxiliary Receipt
    $(document).off('click', '#deleteReceiptRow');
    $(document).on('click', '#deleteReceiptRow', function(e){
        e.preventDefault();
        var row = $(this).parent().parent();
        var totalRows = $('#table_auxiliar_receipt_temp tbody tr').length;
        idAuxiliarySeat  = $(this).data('id');
        url = $(this).data('url');
        url = url + '/deleteDetail/' + idAuxiliarySeat;
        data.idAuxiliarySeat = idAuxiliarySeat;
        ajaxForm(url, 'delete', data, null, 'true')
        .done( function (response) {
            if(response.success){
                $.unblockUI();
                if(totalRows == 1){
                    bootbox.alert('<p class="success-ajax">'+response.message.message+'</p>', function(){
                        location.reload();
                    });
                }else{
                    row.remove();
                    bootbox.alert('<p class="success-ajax">'+response.message.message+'</p>');
                    $('#totalAuxiliaryReceipt').val(response.message.total);
                }
            }else{
                messageErrorAjax(response);
            }
        });
    });

    $(document).off('click', '#saveAuxiliaryReceipt');
    $(document).on('click', '#saveAuxiliaryReceipt', function(e){
        var urlTpl = getTpl('receipt','deposit');
        var modal;
        var url;
        url = $(this).data('url');
        $.get(urlTpl)
        .done(function (q) {
            console.log(url);
            modal = compileTpl(q,{'banks':banks});
            url = url + '/status';
            var token = $('#tokenAuxiliaryReceipt').val();
            bootbox.dialog({
                message: modal,
                title: "Depósitos del Recibo",
                size: "large",
                animate: true,
                className: "my-modal",
                buttons: {
                    success: {
                        label: "Grabar",
                        className: "btn-success",
                        callback: function() {
                            var accountDepositAuxiliaryReceipt = [];
                            var dateDepositAuxiliaryReceipt    = [];
                            var amountDepositAuxiliaryReceipt  = [];
                            var numberDepositAuxiliaryReceipt  = [];
                            $(".accountDepositAuxiliaryReceipt").each(function(index,value){
                                accountDepositAuxiliaryReceipt[index] = $(this).val();
                            });
                            $(".numberDepositAuxiliaryReceipt").each(function(index,value){
                                numberDepositAuxiliaryReceipt[index] = $(this).val();
                            });
                            $(".dateDepositAuxiliaryReceipt").each(function(index,value){
                                dateDepositAuxiliaryReceipt[index] = $(this).val();
                            });
                            $(".amountDepositAuxiliaryReceipt").each(function(index,value){
                                amountDepositAuxiliaryReceipt[index] = $(this).val();
                            });
                            data.token                           = $('#tokenAuxiliaryReceipt').val();
                            data.accountDepositAuxiliaryReceipt  = accountDepositAuxiliaryReceipt;
                            data.numberDepositAuxiliaryReceipt   = numberDepositAuxiliaryReceipt;
                            data.dateDepositAuxiliaryReceipt     = dateDepositAuxiliaryReceipt;
                            data.amountDepositAuxiliaryReceipt   = amountDepositAuxiliaryReceipt;
                            ajaxForm(url,'post',data, null, 'true')
                            .done( function (data) {
                                if(data.success){
                                    $.unblockUI();
                                    $('body').append(data.message.data);
                                    window.print();
                                    $('.print-receipt').remove();
                                    /*$.unblockUI();
                                    bootbox.alert('<p class="success-ajax">'+data.message.msg+'</p>', function(){

                                    });*/
                                }else{
                                    messageErrorAjax(data);
                                }
                            });
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                    }
                }
            });
        });
    });
});