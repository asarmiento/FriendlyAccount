/**
 * Created by Amwar on 05/03/2017.
 */
$(function() {
    var data = {};
    /**
     * Created by anwarsarmiento on 6/12/16.
     */
    /**
     * Modelo de Vehiculos
     */

//Save TypeSeat
    $(document).off('click', '#saveCellphone');
    $(document).on('click', '#saveCellphone', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/save';
        data.customer_idCellphone       = $('#customerCellphone').val();
        data.brand_idCellphone          = $('#brandsCellphone').val();
        data.modelWorkshop_idCellphone  = $('#modelWorkshopCellphone').val();
        data.serieCellphone             = $('#serieCellphone').val();
        data.passwordCellphone          = $('#passwordCellphone').val();
        data.priorityCellphone          = $('#priorityCellphone').val();
        data.costCellphone              = $('#costCellphone').val();
        data.date_of_receiptCellphone   = $('#dateOfReceiptCellphone').val();
        data.date_of_deliveryCellphone  = $('#dateOfDeliveryCellphone').val();
        data.authorizedCellphone        = $('#authorizedCellphone').val();
        data.authorizedSignCellphone    = $('#authorizedSignCellphone').val();
        data.equipmentCellphone         = $('#equipmentCellphone').val();
        data.colorCellphone             = $('#colorCellphone').val();
        data.otherTypeCellphone         = $('#otherTypeCellphone').val();
        data.chargerCellphone           = $('#chargerCellphone').bootstrapSwitch('state');
        data.chargerSeriesCellphone     = $('#chargerSeriesCellphone').val();
        data.caseCellphone              = $('#caseCellphone').bootstrapSwitch('state');
        data.physicalSignsCellphone     = $('#physicalSignsCellphone').val();
        data.additionalRequestsCellphone= $('#additionalRequestsCellphone').val();
        data.reportedProblemsCellphone  = $('#reportedProblemsCellphone').val();
        data.firmCellphone              = $('#firmCellphone').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                messageAjax(data);
            });
    });

    $(document).off('click', '#technicalCellphone');
    $(document).on('click', '#technicalCellphone', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/technical';
        data.diagnosisCellphone         = $('#diagnosisCellphone').val();
        data.work_doneCellphone         = $('#workDoneCellphone').val();
        data.answer_usedCellphone       = $('#answerUsedCellphone').val();
        data.recommendationsCellphone   = $('#recommendationsCellphone').val();
        data.deliveredCellphone         = $('#deliveredCellphone').val();
        data.final_costCellphone        = $('#finalCostCellphone').val();
        data.customerCellphone          = $('#customerCellphone').val();
        data.technicalCellphone         = $('#technicalCellphone').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                messageAjax(data);
            });
    });

    dataTable('#table_cellphone', 'Telefonos en Reparaciones');

    $('#brandsCellphone').on("change", function(e){
        var $this = $(this);
        var $modelWorkshopCellphone = $('#modelWorkshopCellphone');
        $.post('/institucion/inst/taller-de-celulares/models', {brand:$this.val()})
        .done(function(response){
            var options = '';
            response.forEach(function(value){
                options += '<option value="'+ value.token +'">'+ value.name +'</option>';
            });
            $modelWorkshopCellphone.html(options);
        }).fail(function(error){
            alert("No se pudo obtener modelos de la marca.");
        })
    });

});
