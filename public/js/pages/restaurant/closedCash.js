/**
 * Created by Amwar on 03/01/2017.
 */
$(function() {
    var data = {};

//Save TypeSeat
    $(document).off('click', '#searchClosed');
    $(document).on('click', '#searchClosed', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/reporte';
        data.dateIClosedCash = $('#dateI').val();
        data.dateFClosedCash = $('#dateF').val();
        ajaxForm(url, 'get', data, null, 'true')
            .done(function (data) {
                // messageAjax(data);
                console.log(data.message);
                $.unblockUI();
                window.open('/institucion/inst/'+url);
                location.reload();
            });
    });

});