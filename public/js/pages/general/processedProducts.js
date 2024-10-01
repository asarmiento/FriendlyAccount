/**
 * Created by anwarsarmiento on 27/5/17.
 */
$(function() {
    var data = {};

    /**
     * productos cocidos
     */

    /* Save Marcas*/
    $(document).off('click', '#saveProcessedProduct');
    $(document).on('click', '#saveProcessedProduct', function(e){
        e.preventDefault();
        url = $(this).data('url');
        url = 'institucion/inst/' + url + '/save';
        data.nameProcessedProduct  = $('#nameProcessedProduct').val();
        data.codeProcessedProduct  = $('#codeProcessedProduct').val();
        data.typeProcessedProduct  = $('#typeProcessedProduct').val();
        data.priceProcessedProduct = $('#priceProcessedProduct').val();
        data.numberOfDishesProcessedProduct = $('#numberOfDishesProcessedProduct').val();
        ajaxForm(url,'post',data)
            .done( function (data) {
                messageAjax(data);
            });
    });

    /* update Marcas*/
    $(document).off('click', '#updateProcessedProduct');
    $(document).on('click', '#updateProcessedProduct', function(e){
        e.preventDefault();
        url = $(this).data('url');
        url = 'institucion/inst/' + url + '/update';
        data.nameProcessedProduct  = $('#nameProcessedProduct').val();
        data.codeProcessedProduct  = $('#codeProcessedProduct').val();
        data.typeProcessedProduct  = $('#typeProcessedProduct').val();
        data.token  = $('#codeProcessedProduct').data('token');
        data.priceProcessedProduct = $('#priceProcessedProduct').val();
        data.numberOfDishesProcessedProduct = $('#numberOfDishesProcessedProduct').val();
        ajaxForm(url,'post',data)
            .done( function (data) {
                messageAjax(data);
            });
    });


    /**
     * recetas de productos cocidos
     */

    /* Save Marcas*/
    $(document).off('click', '#saveRecipes');
    $(document).on('click', '#saveRecipes', function(e){
        e.preventDefault();
        url = $(this).data('url');
        url = 'institucion/inst/' + url + '/save';
        data.rawProductRecipes  = $('#rawProductRecipes').val();
        data.amountRecipes  	= $('#amountRecipes').val();
        data.processedProductRecipes  	= $('#amountRecipes').data('token');
        data.unitsRecipes 		= $('#unitsRecipes').val();
        ajaxForm(url,'post',data)
            .done( function (data) {
                messageAjax(data);
            });
    });


    dataTable('#table_processedProduct', 'Productos Elaborados');


});