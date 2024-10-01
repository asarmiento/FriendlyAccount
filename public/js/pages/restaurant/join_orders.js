/**
 * Created by Amwar on 08/02/2017.
 */
$(function() {
    var data = {};

    $(document).off('click', '#saveJoinOrders');
    $(document).on('click', '#saveJoinOrders', function(e){
        e.preventDefault();
        var url;
        var stateTable = [];
        var idTable = [];
        url = $(this).data('url');
        url = 'institucion/inst/salon/' + url + '/save';
        $('.tableActive').each(function(index){
            stateTable[index] = $(this).prop('checked');
            idTable[index]    = $(this).data('token');
        });
        data.idTableMaster   = $('#idTableMaster').val();
        data.idTable    = idTable;
        data.stateTable = stateTable;
        ajaxForm(url,'post',data)
            .done( function (data) {
               // messageAjax(data);
                $.unblockUI();
                window.open('/'+url);
            });
    });

});