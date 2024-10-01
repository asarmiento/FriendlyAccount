$(function() {
	var data = {};
	// Save Detail Receipt
	$(document).off('click', '#saveDetailReceipt');
	$(document).on('click', '#saveDetailReceipt', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/save';
		data.accoutingPeriodReceipt = $('#accoutingPeriodReceipt').data('token');
		data.dateReceipt            = $('#dateReceipt').val();
		data.receiptNumberReceipt   = $('#receiptNumberReceipt').val();
		data.receivedFromReceipt    = $('#receivedFromReceipt').val();
		data.detailReceipt          = $('#detailReceipt').val();
		data.amountReceipt          = $('#amountReceipt').val();
		data.catalogReceipt         = $("#catalogReceipt").find('option:selected').val();
		data.textCodeCatalogReceipt = $("#catalogReceipt").find('option:selected').data('code');
		data.textNameCatalogReceipt = $("#catalogReceipt").find('option:selected').data('name');
		ajaxForm(url,'post',data, null, 'true')
		.done( function (response) {
			if(response.success){
				$.unblockUI();
				var tr = addItemReceipt(data, response);
				if($('#table_receipt_temp tbody tr:first').exists()){
					$('#table_receipt_temp tbody tr:first').before(tr);
					$('#totalReceipt').val(response.message.total);
				}else{
					$('#table_receipt_temp').removeClass('hide');
					$('#table_receipt_temp tbody').append(tr);
					$('#saveReceipt').removeClass('hide');
					if(!$("#tokenReceipt").exists()){
						var token = '<input id="tokenReceipt" type="hidden" value="'+ response.message.token +'">';
						$("#table_receipt_temp tbody").prepend(token);
					}
					$('#totalReceipt').val(response.message.total);
				}
			}else{
				messageErrorAjax(response);
			}
		});
	});

	//Delete Receipt
	$(document).off('click', '#deleteReceipt');
	$(document).on('click', '#deleteReceipt', function(e){
		e.preventDefault();
		var row       = $(this).parent().parent();
		var totalRows = $('#table_receipt_temp tbody tr').length;
		var idSeating = $(this).data('id')
		var url       = $(this).data('url');
		url = url + '/deleteDetail/' + idSeating;
		data.idSeating = idSeating;
		ajaxForm(url, 'delete', data, null, 'true')
			.done( function (response) {
				if(response.success){
					$.unblockUI();
					if(totalRows == 1){
						bootbox.alert('<p class="success-ajax">'+response.message.message+'</p>', function(){
							location.reload();
						});
					}else{
						row.next('tr').remove();
						row.remove();
						bootbox.alert('<p class="success-ajax">'+response.message.message+'</p>');
						$('#totalReceipt').val(response.message.total);
					}
				}else{
					messageErrorAjax(response);
				}
			});
	});

	// Save Receipt
	$(document).off('click', '#saveReceipt');
	$(document).on('click', '#saveReceipt', function(e){
		e.preventDefault();
		var urlTpl = getTpl('receipt','deposit');
		var modal ; var url;
		url = $(this).data('url');
		$.get(urlTpl)
		.done(function (q) {
			modal =	compileTpl(q, {'banks':banks});
			url = url + '/status';
			var token = $('#tokenReceipt').val();
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
							data.token                           = token;
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