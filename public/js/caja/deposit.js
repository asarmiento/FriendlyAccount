/**
 * Created by anwar on 20/07/16.
 */
$(function(){
    //setup Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var data = {};
$(document).off('click', '#saveDepositCourtCase');
$(document).on('click', '#saveDepositCourtCase', function(e){
    e.preventDefault();
    var url;
    url = $(this).data('url');
    url = url + '/deposit/save';
    data.bankDeposits = $('#bankDeposits').val();
    data.numberDeposits  = $('#numberDeposits').val();
    data.amountDeposits  = $('#amountDeposits').val();
    data.codeReceiptDeposit  = $('#codeReceiptDeposit').val();
    data.dateDeposits  = $('#dateDeposits').val();
    ajaxForm(url, 'post', data, null, 'true')
        .done(function (response) {
            messageAjax(response);
        });
});

    $(document).off('click', '.deleteDeposit');
    $(document).on('click', '.deleteDeposit', function(e){
        e.preventDefault();
        var url;
        url = $(this).data('url');
        token = $(this).data('token');
        url = url + '/delete';
        data.token = token;
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (response) {
                messageAjax(response);
            });
    });


});