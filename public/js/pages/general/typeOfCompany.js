/**
 * Created by anwarsarmiento on 31/1/17.
 */
$(function() {
    var data = {};
    /**
     * Created by anwarsarmiento on 31/01/17.
     */
    /**
     * Tipos de Empresas
     */

    //Save TypeSeat
    $(document).off('click', '#saveTypeOfCompany');
    $(document).on('click', '#saveTypeOfCompany', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/save';
        data.nameTypeOfCompany = $('#nameTypeOfCompany').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                 messageAjax(data);
            });
    });



    //Update TypeSeat
    $(document).off('click', '#updateTypeOfCompany');
    $(document).on('click', '#updateTypeOfCompany', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/update';
        data.nameTypeOfCompany = $('#nameTypeOfCompany').val();
        data.tokenTypeOfCompany = $('#nameTypeOfCompany').data('token');
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                messageAjax(data);
            });
    });

dataTable('#table_typeOfCompany','No hay tipos de empresas existentes');

});