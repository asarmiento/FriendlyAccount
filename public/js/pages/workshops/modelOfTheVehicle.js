$(function() {
    var data = {};
    /**
     * Created by anwarsarmiento on 6/12/16.
     */
    /**
     * Modelo de Vehiculos
     */

//Save TypeSeat
    $(document).off('click', '#saveModelOfTheVehicle');
    $(document).on('click', '#saveModelOfTheVehicle', function (e) {
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/save';
        data.nameModelOfTheVehicle = $('#nameModelOfTheVehicle').val();
        data.brandsModelOfTheVehicle = $('#brandsModelOfTheVehicle').val();
        ajaxForm(url, 'post', data, null, 'true')
            .done(function (data) {
                messageAjax(data);
            });
    });

    dataTable('#table_model_Of_The_Vehicle', 'Modelos de Vehiculos');

});