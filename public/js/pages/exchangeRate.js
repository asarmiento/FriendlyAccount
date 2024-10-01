/**
 * Created by Amwar on 24/01/2017.
 */
$(function() {
    var data = {};
    /**
     * Created by anwarsarmiento on 24/01/17.
     */
    /**
     * Facturas de Ventas
     */

    //Save TypeSeat
    $(document).off('click', '#saveExchangeRate');
    $(document).on('click', '#saveExchangeRate', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/save';
        data.dateExchangeRate = $('#dateExchangeRate').val();
        data.buyExchangeRate = $('#buyExchangeRate').val();
        data.saleExchangeRate = $('#saleExchangeRate').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                   messageAjax(data);
            });
    });


    //Save TypeSeat
    $(document).off('click', '#eliminarExchange');
    $(document).on('click', '#eliminarExchange', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        token = $(this).data('token');

        url = 'eliminar/'+url +'/' + token;
         ajaxForm(url, 'get', data, null, 'true')
            .done(function (data) {
                messageAjax(data);
            });
    });

});
