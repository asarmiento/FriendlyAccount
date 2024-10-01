var server = "/";

/**
 * [exists description]
 * @return {[type]} [description]
 */
jQuery.fn.exists = function() {
	return this.length>0;
}

// http://stackoverflow.com/questions/13162186/javascript-add-thousand-seperator-and-retain-decimal-place
var format = function(n, sep, decimals) {
    sep = sep || "."; // Default to period as decimal separator
    decimals = decimals || 2; // Default to 2 decimals
    return n.toLocaleString('en-US').split(sep)[0]
        + sep
        + n.toFixed(decimals).split(sep)[1];
}

// http://stackoverflow.com/questions/1267283/how-can-i-create-a-zerofilled-value-using-javascript/9744576#9744576
var paddy = function (n, p, c) {
    var pad_char = typeof c !== 'undefined' ? c : '0';
    var pad = new Array(1 + p).join(pad_char);
    return (pad + n).slice(-pad.length);
};
//var fu = paddy(14, 5); // 00014

/**
 * @param  {[string]} selector [id table]
 * @param  {[string]} list [comment the table]
 * @return {[dataTable]}   [table with options dataTable]
 */
var dataTable = function(selector, list){
	var options = {
		"order": [
            [0, "asc"]
        ],
        "bLengthChange": true,
        //'iDisplayLength': 7,
        "oLanguage": {
        	"sLengthMenu": "_MENU_ registros por página",
        	"sInfoFiltered": " - filtrada de _MAX_ registros",
            "sSearch": "Buscar: ",
            "sZeroRecords": "No existen, " + list,
            "sInfoEmpty": " ",
            "sInfo": 'Mostrando _END_ de _TOTAL_',
            "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
            }
        }
	};
	$(selector).DataTable(options);
};

/**
 * [messageAjax - Response message after request ]
 * @param  {[json]} data [description messages error after request]
 * @return {[alert]}     [errors in alert]
 */
var box;
var messageAjax = function(data, no_bootbox) {
	//console.log(data.errors);
	$.unblockUI();
	if(data.success){
		if(data.message.redirect)
		{
			window.location.href = data.message.href;
		}else{
			if(! no_bootbox )
			{
				bootbox.alert('<p class="success-ajax">'+data.message+'</p>', function(){
					location.reload();
				});
			}
		}
	}
	else{
		messageErrorAjax(data);
	}
};

/**
 * [messageErrorAjax description]
 * @param  {[type]} error [description]
 * @return {[type]}       [description]
 */
var messageErrorAjax = function(data){
	$.unblockUI();
	var errors = data.errors;
	var error  = "";
	if($.type(errors) === 'string'){
		error = data.errors;
	}else{
		for (var element in errors){
			if(errors.hasOwnProperty(element)){
				error += errors[element] + '<br>';
			}
		}
	}
	bootbox.alert('<p class="error-ajax">'+error+'</p>');
};

/**
 * [addActive - Add class for submenu active]
 * @param {[string]} element [submenu]
 */
var addActive = function (element) {
	element.find('.icon-menu').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down');
	element.addClass('active');
	element.find('.nav').show('slide');
};

/**
 * [removeActive - Remove class for submenu active]
 * @param {[string]} element [submenu]
 */
var removeActive = function (element) {
	$('.active').find('.icon-menu').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
	$('.active').find('.nav').hide('slide');
	$('.active').removeClass('active');
};

/**
 * [loadingUI - Message before ajax for request]
 * @param  {[string]} message [message for before ajax]
 * @return {[message]}        [blockUI response with message]
 */
var loadingUI = function (message, img){
	if(img){
		var msg = '<h2><img style="margin-right: 30px" src="' + server + 'images/spiffygif.gif" >' + message + '</h2>';
	}else{
		var msg = '<h2>' + message + '</h2>';
	}
    $.blockUI({ css: {
        border: 'none',
        padding: '15px',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
        opacity: 0.5,
        color: '#fff'
    }, message: msg});
};

/**
 * [addInputText description]
 * @param {[type]} data     [description]
 * @param {[type]} selector [description]
 */
var addInputText = function(data, selector){
	var div = '<div class="input-group">';
		div+= '<span class="input-group-addon"><i class="fa fa-tag"></i></span>';
		div+= '<input id="typeAuxiliarySeat" class="form-control" type="text" value="'+ data.textTypeAuxiliarySeat +'" data-type="text" data-token="'+ data.typeAuxiliarySeat +'" disabled>';
		div+= '</div>';
	selector.append(div);
};

var addCurrencieCash = function(data, selector){

	var tr  = "<tr>";
	    tr += '<td class="text-center"><button href="#" class="btn btn-danger btn-xs delete_currencie"><i class="fa fa-trash-o"></i></button></td>';
		tr += '<td class="currencie" data-id="'+data.token+'" data-value="'+data.value+'" data-amount="'+data.amount+'">'+data.name+'</td>';
        tr += '<td class="amount">'+data.amount+'</td>';
        tr += '<td class="total">'+(data.value * data.amount)+'</td>';
        tr += '</tr>';
    selector.append(tr);

    calculateTotalCurrencie();
}

var replaceComa = function(number){
	while(number.indexOf(',') != -1){
		number = number.replace(',','');
	}
	return number;
};

var calculateTotalCurrencie = function(){
	var tot = 0;
	$('.total').each(function(i,v){
		tot += parseFloat(replaceComa($(this).text()));
	});
	$('#total_currencies').text(format(tot, ".",2));
	$('#cash').text(format(tot));
	var leftover = parseFloat(replaceComa($('#total_sales').text())) - tot;
	$('#leftover').text(format(parseFloat(Math.round(leftover * 100) / 100)));
}

/**
 * [ajaxForm - setup ajax for request]
 * @param  {[string]} url  [description]
 * @param  {[string]} type [description]
 * @param  {[json]} data [description]
 * @return {[type]}      [description]
 */
var ajaxForm = function (url, type, data, msg, school){
	var message;
	var path = server + url;
	if(msg){
		message = msg
	}else{
		if(type == 'post'){
			message = 'Registrando Datos';
		}else{
			message = 'Actualizando Registros';
		}
	}
	if(school){
		path = server + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] + ('/') +url;
	}
	return $.ajax({
				url: path,
			    type: type,
			    data: {data: JSON.stringify(data)},
			    datatype: 'json',
			    beforeSend: function(){
		    		loadingUI(message, 'img');
			    },
			    error:function(xhr, status, error){
			    	$.unblockUI();
			    	if(xhr.status == 401){
			    		bootbox.alert("<p class='red'>No estas registrado en la aplicación.</p>", function(response){
			    				location.reload();
			    		});
			    	}else{
		    			bootbox.alert("<p class='red'>No se pueden grabar los datos.</p>");
			    	}
				}
	});
};

/**
 * [addItemRow description]
 * @param {[type]} data     [description]
 * @param {[type]} response [description]
 * @param {[type]} receipt  [description]
 */
var addItemRow = function (data, response, receipt){
	var codeArray = data.codeStudent.split(' ');
	var key = $('.Table-content').length + 1;
	var tr = '<tr class="Table-content">';
			//tr+= '<td>'+ key + '</td>';
			tr+= '<td>'+ codeArray[0] + '</td>';
			tr+= '<td>'+ codeArray[2] + ' ' + codeArray[3] + codeArray[4] + ' ' +codeArray[5] +  '</td>';
			if(receipt){
				tr+= '<td>'+ data.detailAuxiliaryReceipt + '</td>';
				tr+= '<td class="text-center">'+ data.amountAuxiliaryReceipt +'</td>';
				tr+= '<td class="text-center"><a id="deleteReceiptRow" data-url="recibos-auxiliares" href="#" data-id="'+ response.message.id +'"><i class="fa fa-trash-o"></i></a></td>';
			}else{
				tr+= '<td>'+ data.detailAuxiliarySeat + '</td>';
				tr+= '<td>'+ data.textTypeAuxiliarySeat + '</td>';
				tr+= '<td>'+ Number(data.amountAuxiliarySeat).toFixed(2) +'</td>';
				tr+= '<td class="text-center"><a id="deleteDetailRow" data-url="asientos-auxiliares" href="#" data-id="'+ response.message.id +'"><i class="fa fa-trash-o"></i></a></td>';
			}
		tr+= '</tr>';
	return tr;
};

/**
 * [addItemSeat description]
 * @param {[type]} data     [description]
 * @param {[type]} response [description]
 */
var addItemSeat = function (data, response){
	var textAccountSeating     = data.textAccountSeating.split(' ');
	var textTypeSeating        = data.textTypeSeating;
	var textAccountPartSeating = data.textAccountPartSeating;
	var amountSeating          = data.amountSeating;
	var totalSeating           = amountSeating.reduce(function(previousValue, currentValue){
									return parseFloat(previousValue) + parseFloat(currentValue);
								});
	var tr = '<tr class="Table-content">';
	var textAccount = '';
			tr+= '<td>'+ textAccountSeating[0] + '</td>';
			for (var i = 1; i < textAccountSeating.length; i++) {
				textAccount += ' '+textAccountSeating[i];
			}
			tr+= '<td>'+ textAccount +'</td>';
			if(textTypeSeating.toLowerCase() == 'debito'){
				tr+= '<td class="text-center">'+ totalSeating + '</td>';
				tr+= '<td class="text-center">'+ '-' + '</td>';
			}else{
				tr+= '<td class="text-center">'+ '-' + '</td>';
				tr+= '<td class="text-center">'+ totalSeating + '</td>';
			}
			tr+= '<td class="text-center"><a id="deleteDetailSeating" data-url="asientos" href="#" data-id="'+ response.message.id +'"><i class="fa fa-trash-o"></i></a></td>';
		tr+= '</tr>';
		tr+= '<tr class="Table-description">';
			tr+= '<td>'+ data.detailSeating + '</td>';
		tr+= '</tr>';
		/* Childs */
		for (var i = 0; i < amountSeating.length; i++) {
			var amountPart = amountSeating[i];
			var textPart   = textAccountPartSeating[i].split(' ');
			var textAccountPart = '';
			for (var j = 1; j < textPart.length; j++) {
				textAccountPart += ' '+textPart[j];
			}
			tr+= '<tr class="Table-description">';
				tr+= '<td>'+ textPart[0] + '</td>';
				tr+= '<td>'+ textAccountPart + '</td>';
				if(textTypeSeating.toLowerCase() == 'debito'){
					tr+= '<td class="text-center">'+ '-' + '</td>';
					tr+= '<td class="text-center">'+ amountPart + '</td>';
				}else{
					tr+= '<td class="text-center">'+ amountPart + '</td>';
					tr+= '<td class="text-center">'+ '-' + '</td>';
				}
			tr+= '</tr>';
		};
	return tr;
};

/**
 * [addItemReceipt description]
 * @param {[type]} data     [description]
 * @param {[type]} response [description]
 */
var addItemReceipt = function (data, response){
	var tr = '<tr class="Table-content">';
			tr+= '<td>'+ data.textCodeCatalogReceipt + '</td>';
			tr+= '<td>'+ data.textNameCatalogReceipt + '</td>';
			tr+= '<td class="text-center">'+ data.amountReceipt + '</td>';
			tr+= '<td class="text-center"><a id="deleteReceipt" data-url="recibos" href="#" data-id="'+ response.message.id +'"><i class="fa fa-trash-o"></i></a></td>';
		tr+= '</tr>';
		tr+= '<tr class="Table-description" colspan="4">';
			tr+= '<td>'+ data.detailReceipt +'</td>';
		tr+= '</tr>';
		return tr;
};

/**
 * [getTpl description]
 * @param  {[type]} view [description]
 * @param  {[type]} type [description]
 * @return {[type]}      [description]
 */
var getTpl = function(view, type){
	return "/templates/" + view + "/" + type +".html";
};

/**
 * [compileTpl description]
 * @param  {[type]} html [description]
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
var compileTpl = function(html, data){
	helpersHandlebars();
	var tpl = Handlebars.compile(html);
	var html = tpl(data);
	return html;
};

/**
 * [helperHandlebars description]
 * @return {[type]} [description]
 */
var helpersHandlebars = function(){
	Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b) // Or === depending on your needs
	        return opts.fn(this);
	    else
	        return opts.inverse(this);
	});

	Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
	    switch (operator) {
	        case '==':
	            return (v1 == v2) ? options.fn(this) : options.inverse(this);
	        case '===':
	            return (v1 === v2) ? options.fn(this) : options.inverse(this);
	        case '<':
	            return (v1 < v2) ? options.fn(this) : options.inverse(this);
	        case '<=':
	            return (v1 <= v2) ? options.fn(this) : options.inverse(this);
	        case '>':
	            return (v1 > v2) ? options.fn(this) : options.inverse(this);
	        case '>=':
	            return (v1 >= v2) ? options.fn(this) : options.inverse(this);
	        case '&&':
	            return (v1 && v2) ? options.fn(this) : options.inverse(this);
	        case '||':
	            return (v1 || v2) ? options.fn(this) : options.inverse(this);
	        default:
	            return options.inverse(this);
	    }
	});

	Handlebars.registerHelper("math", function(lvalue, operator, rvalue, options) {
	    lvalue = parseFloat(lvalue);
	    rvalue = parseFloat(rvalue);

	    return {
	        "+": (lvalue + rvalue).toFixed(2),
	        "-": (lvalue - rvalue).toFixed(2),
	        "*": (lvalue * rvalue).toFixed(2),
	        "/": (lvalue / rvalue).toFixed(2),
	        "%": (lvalue % rvalue).toFixed(2)
	    }[operator];
	});

	Handlebars.registerHelper("fixed", function(text, len, options){
		return text.substring(0,len);
	});

	Handlebars.registerHelper("formatDate", function(date, options){
		var arrDate = date.split('-');
	    var day = arrDate[2];
	    var month = arrDate[1];
	    var year = arrDate[0];
	    return day+"/"+month+"/"+year;
	});
};

var addOrderSalon = function(message){
	var response = JSON.parse(message.data);
	var urlTpl   = getTpl('orders', 'salon');
    $.get(urlTpl)
    .done(function(tpl){
        var html = compileTpl(tpl, response);
        if($('.card-restaurant.kitchen .pending').exists()){
        	$('.card-restaurant.kitchen .pending').remove();
        }
        $('.card-restaurant.kitchen').append(html);
    });
};

var iva = 0.13;

$(function(){
	//setup Ajax
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	var data = {};

	//Event menu expand
	$('.submenu').on('click', function(e){
		e.preventDefault();
		var element = $(this);
		var exp = false;
		if($(this).hasClass('active')){
			exp = true;
		}
		removeActive();
		if(!$(this).hasClass('active')){
			if(!exp){
				addActive(element);
			}
		}
	});

	$('.submenu li a').on('click', function(){
		window.location.href = $(this).attr('href');
	});

	//Switch Checkbox
	if( $("[name='status-checkbox']").exists() ){
		$("[name='status-checkbox']").bootstrapSwitch({size:'normal'});
	}

	if( $("[name='task-checkbox']").exists() ){
		$("[name='task-checkbox']").bootstrapSwitch({size:'normal'});
	}

	if( $(".role-checkbox").exists() ){
		$(".role-checkbox").bootstrapSwitch({size:'normal'});
	}

	if( $("[name='iviBuy-checkbox']").exists() ){
		$("[name='iviBuy-checkbox']").bootstrapSwitch({size:'normal'});
	}

	//dateRangepicker
	if( $('#txtDate').exists() ){
		$("#txtDate").daterangepicker(
			{
				locale:{
					monthNames: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Set','Oct','Nov','Dic'],
					applyLabel: 'Aceptar',
					cancelLabel: 'Cancelar',
					fromLabel: 'Desde',
					toLabel: 'Hasta'
				},
				minViewMode: 'month',
			    format: 'MM/YYYY',
			    startDate: $('#startDate').val(),
			    endDate: $('#endDate').val(),
			    minDate: $('#startDate').val(),
			    maxDate: $('#endDate').val(),
			    hideFormInputs: false,
			    opens: 'right',
			    autoApplyClickedRange : true
			},
			function(start, end, label) {
			 	$('#txtDate').val(start.format('MM/YYYY')+'-'+end.format('MM/YYYY'));
			}
		);
	}

	// Select 2
	if($('.select2').exists()){
		$('.select2').select2({
			language: 'es'
		})
	}

	//Events

	//txtDate Range Dates
	$(document).off('change', '#txtDate');
	$(document).on('change', '#txtDate', function(){
		var range = $('#txtDate').val();
		var rangeArray = range.split(' - ');
		var rangeIni = rangeArray[0].split('/');
		var rangeFin = rangeArray[1].split('/');
		range = rangeIni[1]+rangeIni[0]+'-'+rangeFin[1]+rangeFin[0];
		var path = 'balance-comprobacion/' + range;
		window.location.href = path;
	});

	//Events Roles
	$(document).off('click', '.form-role .checkAll');
	$(document).on('click', '.form-role .checkAll', function(e){
		e.preventDefault();
		$(this).parent().parent().find('.role-checkbox').bootstrapSwitch('state', true, true);
	});

	$(document).off('click', '.form-role .unCheckAll');
	$(document).on('click', '.form-role .unCheckAll', function(e){
		e.preventDefault();
		$(this).parent().parent().find('.role-checkbox').bootstrapSwitch('state', false, false);
	});

	$(document).off('click', '#checkAll');
	$(document).on('click', '#checkAll', function(e){
		e.preventDefault();
		$('.role-checkbox').bootstrapSwitch('state', true, true);
	});

	$(document).off('click', '#unCheckAll');
	$(document).on('click', '#unCheckAll', function(e){
		e.preventDefault();
		$('.role-checkbox').bootstrapSwitch('state', false, false);
	});

	if( $(".menu-role").exists() ){
		$(".menu-role").each(function(index){
		  	if($(this).find('div.row').length == 0){
		    	$(this).remove();
		  	}
		  	$('.form-role .col-sm-6 fieldset').matchHeight();
		});
	}

	//Redirect School
	$(document).off("click", ".routeSchool");
	$(document).on("click", ".routeSchool", function(e){
		e.preventDefault();
		var token  = $(this).data('token');
		var url    = 'route-institucion';
		data.token = token;
		ajaxForm(url, 'post', data, 'Redirigiendo...')
		.done(function (data) {
			$.unblockUI();
			if(data.success){
				window.location.href = server + 'institucion/inst/';
			}else{
				bootbox.alert(data.errores);
			}
		});
	});

	//Add Deposit
	$(document).off('click', '#addDeposit');
	$(document).on('click', '#addDeposit', function(e){
		e.preventDefault();
		if($('.totalDeposit').length == 1){
			$('#removeDeposit').removeClass('hide');
		}
		var account = $('.totalDeposit aside:first').clone(true,true);
		account.appendTo('.totalDeposit');
	});

	//Delete Deposit
	$(document).on('click', '#removeDeposit', function(e){
		e.preventDefault();
		var element = $(this);
		var account = $('.totalDeposit aside:first');
		account.remove();
		if($('.totalDeposit aside').length == 1){
			element.addClass('hide');
		}
	});

	//ReportPdf CourtCase
	$(document).off('click', '#reportCourtCase');
	$(document).on('click', '#reportCourtCase', function(e){
		e.preventDefault();
		var href = $(this).attr('href');

			window.open(href+'/');

	});

	/**
	 * School
	 */

	//Save School
	$(document).off('click', '#saveSchool');
	$(document).on('click', '#saveSchool', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url =  'save-' + url;
		data.nameSchool       = $('#nameSchool').val();
		data.charterSchool    = $('#charterSchool').val();
		data.routeSchool      = $('#routeSchool').val();
		data.phoneOneSchool   = $('#phoneOneSchool').val();
		data.phoneTwoSchool   = $('#phoneTwoSchool').val();
		data.faxSchool        = $('#faxSchool').val();
		data.addressSchool    = $('#addressSchool').val();
		data.townSchool       = $('#townSchool').val();
		data.monthFirstSchool = $('#monthFirstSchool').val();
		data.monthEndSchool   = $('#monthEndSchool').val();
		data.statusSchool     = $('#statusSchool').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update School
	$(document).off('click', '#updateSchool');
	$(document).on('click', '#updateSchool', function(e){
		e.preventDefault();
		var url;
		var idSchool;
		idSchool = $('#idSchool').val();
		url = $(this).data('url');
		url = 'update-' + url + '/' + idSchool;
		data.idSchool         = idSchool;
		data.nameSchool       = $('#nameSchool').val();
		data.charterSchool    = $('#charterSchool').val();
		data.routeSchool      = $('#routeSchool').val();
		data.phoneOneSchool   = $('#phoneOneSchool').val();
		data.phoneTwoSchool   = $('#phoneTwoSchool').val();
		data.faxSchool        = $('#faxSchool').val();
		data.addressSchool    = $('#addressSchool').val();
		data.townSchool       = $('#townSchool').val();
		data.monthFirstSchool = $('#monthFirstSchool').val();
		data.monthEndSchool   = $('#monthEndSchool').val();
		data.statusSchool     = $('#statusSchool').bootstrapSwitch('state');
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active School
	$(document).off('click', '#activeSchool');
	$(document).on('click', '#activeSchool', function(e){
		e.preventDefault();
		var url;
		var idSchool  = $(this).parent().parent().find('.school_number').text();
		url           = $(this).data('url');
		url           = 'active-' + url + '/' + idSchool;
		data.idSchool = idSchool;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete School
	$(document).off('click', '#deleteSchool');
	$(document).on('click', '#deleteSchool', function(e){
		e.preventDefault();
		var url;
		var idSchool  = $(this).parent().parent().find('.school_number').text();
		url           = $(this).data('url');
		url           = 'delete-' + url + '/' + idSchool;
		data.idSchool = idSchool;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_school', 'instituciones');

	/**
	 * End School
	 */

	/**
	 * Type User
	 */
	//Save Type User
	$(document).off('click', '#saveTypeUser');
	$(document).on('click', '#saveTypeUser', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.nameTypeUser   = $('#nameTypeUser').val();
		data.statusTypeUser = $('#statusTypeUser').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Type User
	$(document).off('click', '#updateTypeUser');
	$(document).on('click', '#updateTypeUser', function(e){
		e.preventDefault();
		var url;
		var idTypeUser;
		idTypeUser = $('#idTypeUser').val();
		url = $(this).data('url');
		url = url + '/update/' + idTypeUser;
		data.idTypeUser     = idTypeUser;
		data.nameTypeUser   = $('#nameTypeUser').val();
		data.statusTypeUser = $('#statusTypeUser').bootstrapSwitch('state');
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active Type User
	$(document).off('click', '#activeTypeUser');
	$(document).on('click', '#activeTypeUser', function(e){
		e.preventDefault();
		var url;
		var id_type_user = $(this).parent().parent().find('.type_user_number').text();
		url              = $(this).data('url');
		url              = url + '/active/' + id_type_user;
		data.idTypeUser = id_type_user;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Type User
	$(document).off('click', '#deleteTypeUser');
	$(document).on('click', '#deleteTypeUser', function(e){
		e.preventDefault();
		var url;
		var id_type_user = $(this).parent().parent().find('.type_user_number').text();
		url              = $(this).data('url');
		url              = url + '/delete/' + id_type_user;
		data.idTypeUser  = id_type_user;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_type_user', 'tipos de usuarios');

	/**
	 * End Type User
	 */

	/**
	 * Tasks
	 */
	//Save Task
	$(document).off('click', '#saveTask');
	$(document).on('click', '#saveTask', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.nameTask   = $('#nameTask').val();
		data.statusTask = $('#statusTask').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Task
	$(document).off('click', '#updateTask');
	$(document).on('click', '#updateTask', function(e){
		e.preventDefault();
		var url;
		var idTask;
		idTask = $('#idTask').val();
		url    = $(this).data('url');
		url    = url + '/update/' + idTask;
		data.idTask     = idTask;
		data.nameTask   = $('#nameTask').val();
		data.statusTask = $('#statusTask').bootstrapSwitch('state');
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active Task
	$(document).off('click', '#activeTask');
	$(document).on('click', '#activeTask', function(e){
		e.preventDefault();
		var url;
		var id_task = $(this).parent().parent().find('.task_number').text();
		url         = $(this).data('url');
		url         = url + '/active/' + id_task;
		data.idTask = id_task;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Task
	$(document).off('click', '#deleteTask');
	$(document).on('click', '#deleteTask', function(e){
		e.preventDefault();
		var url;
		var id_task = $(this).parent().parent().find('.task_number').text();
		url         = $(this).data('url');
		url         = url + '/delete/' + id_task;
		data.idTask = id_task;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_task', 'tareas');

	/**
	 * End Tasks
	 */

	/**
	 * Menu
	 */

	//Save Menu
	$(document).off('click', '#saveMenu');
	$(document).on('click', '#saveMenu', function(e){
		e.preventDefault();
		var url;
		var stateTasks = [];
		var idTasks = [];
		url = $(this).data('url');
		url = url + '/save';
		$('.task_menu').each(function(index){
			stateTasks[index] = $(this).bootstrapSwitch('state');
			idTasks[index]    = $(this).data('id');
		});
		data.nameMenu   = $('#nameMenu').val();
		data.urlMenu    = $('#urlMenu').val();
		data.iconMenu   = $('#iconMenu').val();
		data.idTasks    = idTasks;
		data.stateTasks = stateTasks;
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Menu
	$(document).off('click', '#updateMenu');
	$(document).on('click', '#updateMenu', function(e){
		e.preventDefault();
		var url;
		var idMenu;
		var statusMenu;
		var stateTasks = [];
		var idTasks = [];
		url        = $(this).data('url');
		idMenu     = $('#idMenu').val();
		statusMenu = $('#statusMenu').bootstrapSwitch('state');
		url        = url + '/update/' + idMenu;
		$('.task_menu').each(function(index){
			stateTasks[index] = $(this).bootstrapSwitch('state');
			idTasks[index]    = $(this).data('id');
		});
		data.idMenu     = idMenu;
		data.statusMenu = statusMenu;
		data.nameMenu   = $('#nameMenu').val();
		data.urlMenu    = $('#urlMenu').val();
		data.iconMenu   = $('#iconMenu').val();
		data.idTasks    = idTasks;
		data.stateTasks = stateTasks;
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active Menu
	$(document).off('click', '#activeMenu');
	$(document).on('click', '#activeMenu', function(e){
		e.preventDefault();
		var url;
		var idMenu  = $(this).parent().parent().find('.menu_number').text();
		url         = $(this).data('url');
		url         = url + '/active/' + idMenu;
		data.idMenu = idMenu;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
			location.reload();
		});
	});

	//Delete Menu
	$(document).off('click', '#deleteMenu');
	$(document).on('click', '#deleteMenu', function(e){
		e.preventDefault();
		var url;
		var idMenu  = $(this).parent().parent().find('.menu_number').text();
		url         = $(this).data('url');
		url         = url + '/delete/' + idMenu;
		data.idMenu = idMenu;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_menu', 'menús');

	/**
	 * End Menu
	 */

	/**
	 * Users
	 */
	var routeAction = document.location.pathname.split('/')[1]+'/'+document.location.pathname.split('/')[2];
	if(routeAction === 'usuarios/crear' || routeAction === 'usuarios/editar'){
		localStorage.clear();
		if(localStorage === 'usuarios/crear'){
			var prefetch = '../json/schools.json';
		}else {
			var prefetch = '../../json/schools.json';
		}

		var schools = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: prefetch
	    });
	    schools.initialize();

	    var elt = $('#schools');
	    elt.tagsinput({
			itemValue: 'value',
			itemText: 'text',
			typeaheadjs: {
				name: 'schools',
				displayKey: 'text',
				source: schools.ttAdapter()
			}
	    });

	    if(routeAction === 'usuarios/editar'){
			if($("#hdnSchools").attr('data-id').length === 1){
				var value = $("#hdnSchools").attr('data-id');
				var text  = $("#hdnSchools").attr('data-name');
		    	elt.tagsinput('add', {"value": value, "text": ''+ text})
			}else if($("#hdnSchools").attr('data-id').length > 1){
				var value = $("#hdnSchools").attr('data-id').split(',');
				var text  = $("#hdnSchools").attr('data-name').split(',');
				for(var i = 0; i<value.length; i++){
		    		elt.tagsinput('add', {"value": value[i], "text": '' + text[i]})
		    	}
			}
	    }
	}

	//Save User
	$(document).off('click', '#saveUser');
	$(document).on('click', '#saveUser', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		var schools    = $("#schools").val();
		var arrSchools = schools.split(',');
		data.nameUser       = $('#nameUser').val();
		data.lastUser       = $('#lastUser').val();
		data.emailUser      = $('#emailUser').val();
		data.passwordUser   = $('#passwordUser').val();
		data.typeUserIdUser = $('#typeUser').val();
		data.tokenSupplier  = $('#supplier').val();
		data.schoolsUser    = arrSchools;
		data.statusUser     = $('#statusUser').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update User
	$(document).off('click', '#updateUser');
	$(document).on('click', '#updateUser', function(e){
		e.preventDefault();
		var url;
		var idUser;
		idUser = $('#idUser').val();
		url    = $(this).data('url');
		url    = url + '/update/' + idUser;
		data.idUser        = idUser;
		var schools        = $("#schools").val();
		var arrSchools     = schools.split(',');
		data.nameUser      = $('#nameUser').val();
		data.lastUser      = $('#lastNameUser').val();
		data.emailUser     = $('#emailUser').val();
		data.passwordUser  = null;
		data.idTypeUser    = $('#typeUser').val();
		data.tokenSupplier = $('#supplier').val();
		data.schoolsUser   = arrSchools;
		data.statusUser    = $('#statusUser').bootstrapSwitch('state');
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active User
	$(document).off('click', '#activeUser');
	$(document).on('click', '#activeUser', function(e){
		e.preventDefault();
		var url;
		var idUser  = $(this).parent().parent().find('.user_number').text();
		url         = $(this).data('url');
		url         = url + '/active/' + idUser;
		data.idUser = idUser;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete User
	$(document).off('click', '#deleteUser');
	$(document).on('click', '#deleteUser', function(e){
		e.preventDefault();
		var url;
		var idUser  = $(this).parent().parent().find('.user_number').text();
		url         = $(this).data('url');
		url         = url + '/delete/' + idUser;
		data.idUser = idUser;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_user', 'usuarios');

	/**
	 * End User
	 */

	/**
	 * Type Form
	 */
	//Save Type Form
	$(document).off('click', '#saveTypeForm');
	$(document).on('click', '#saveTypeForm', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.nameTypeForm   = $('#nameTypeForm').val();
		data.statusTypeForm = $('#statusTypeForm').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Type Form
	$(document).off('click', '#updateTypeForm');
	$(document).on('click', '#updateTypeForm', function(e){
		e.preventDefault();
		var url;
		var idTypeForm;
		idTypeForm = $('#idTypeForm').val();
		url = $(this).data('url');
		url = url + '/update/' + idTypeForm;
		data.idTypeForm     = idTypeForm;
		data.nameTypeForm   = $('#nameTypeForm').val();
		data.statusTypeForm = $('#statusTypeForm').bootstrapSwitch('state');
		ajaxForm(url,'put',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active Type Form
	$(document).off('click', '#activeTypeForm');
	$(document).on('click', '#activeTypeForm', function(e){
		e.preventDefault();
		var url;
		var id_type_form = $(this).parent().parent().find('.type_form_number').text();
		url              = $(this).data('url');
		url              = url + '/active/' + id_type_form;
		data.idTypeForm  = id_type_form;
		ajaxForm(url, 'patch', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Type Form
	$(document).off('click', '#deleteTypeForm');
	$(document).on('click', '#deleteTypeForm', function(e){
		e.preventDefault();
		var url;
		var id_type_form = $(this).parent().parent().find('.type_form_number').text();
		url              = $(this).data('url');
		url              = url + '/delete/' + id_type_form;
		data.idTypeForm  = id_type_form;
		ajaxForm(url, 'delete', data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_type', 'tipos');

	/**
	 * Fin Tipos
	 */

	/**
	 * Bancos
	 */
	//Save Bank
	$(document).off('click', '#saveBank');
	$(document).on('click', '#saveBank', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.nameBank    = $('#nameBank').val();
		data.accountBank = $('#accountBank').val();
		data.statusBank  = $('#statusBank').bootstrapSwitch('state');
		ajaxForm(url,'post',data, null,'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Bank
	$(document).off('click', '#updateBank');
	$(document).on('click', '#updateBank', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token       = $('#nameBank').data('token');
		data.nameBank    = $('#nameBank').val();
		data.accountBank = $('#accountBank').val();
		data.statusBank  = $('#statusBank').bootstrapSwitch('state');
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Active Bank
	$(document).off('click', '#activeBank');
	$(document).on('click', '#activeBank', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.bank_name').data('token');
		url        = $(this).data('url');
		url        = url + '/active/' + token;
		data.token = token;
		ajaxForm(url, 'patch', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Bank
	$(document).off('click', '#deleteBank');
	$(document).on('click', '#deleteBank', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.bank_name').data('token');
		url        = $(this).data('url');
		url        = url + '/delete/' + token;
		data.token = token;
		ajaxForm(url, 'delete', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_bank', 'cuentas bancarias');

	/**
	 * Fin Bancos
	 */

	/**
	 * Grados
	 */
	//Save Degree
	$(document).off('click', '#saveDegree');
	$(document).on('click', '#saveDegree', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.codeDegree   = $('#codeDegree').val();
		data.nameDegree   = $('#nameDegree').val();
		data.statusDegree = $('#statusDegree').bootstrapSwitch('state');
		ajaxForm(url,'post',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Degree
	$(document).off('click', '#updateDegree');
	$(document).on('click', '#updateDegree', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token        = $('#codeDegree').data('token');
		data.codeDegree   = $('#codeDegree').val();
		data.nameDegree   = $('#nameDegree').val();
		data.statusDegree = $('#statusDegree').bootstrapSwitch('state');
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});
/*
	//Active Degree
	$(document).off('click', '#activeDegree');
	$(document).on('click', '#activeDegree', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.degree_code').data('token');
		url        = $(this).data('url');
		url        = url + '/active/' + token;
		data.token = token;
		ajaxForm(url, 'patch', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Degree
	$(document).off('click', '#deleteDegree');
	$(document).on('click', '#deleteDegree', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.degree_code').data('token');
		url        = $(this).data('url');
		url        = url + '/delete/' + token;
		data.token = token;
		ajaxForm(url, 'delete', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});*/

	dataTable('#table_degree', 'grados académicos');

	/**
	 * Fin Bancos
	 */

	/**
	 * Notas
	 */
	//Update Degree
	$(document).off('click', '#updateNote');
	$(document).on('click', '#updateNote', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token        = $('#descriptionNote').data('token');
		data.descriptionNote   = $('#descriptionNote').val();
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_note', 'notas');

	/**
	 * Fin Bancos
	 */

	/**
	 * Periodos Contables
	 */
	dataTable('#table_accounting_period', 'notas');

	/**
	 * Fin Periodos Contables
	 */

	/**
	 * Costos
	 */
	//Save Cost
	$(document).off('click', '#saveCost');
	$(document).on('click', '#saveCost', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.yearCost           = $('#yearCost').val();
		data.monthlyPaymentCost = $('#monthlyPaymentCost').val();
		data.tuitionCost        = $('#tuitionCost').val();
		data.degreeSchoolCost   = $('#degreeSchoolCost').val();
		ajaxForm(url,'post',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Cost
	$(document).off('click', '#updateCost');
	$(document).on('click', '#updateCost', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token              = $('#yearCost').data('token');
		data.yearCost           = $('#yearCost').val();
		data.monthlyPaymentCost = $('#monthlyPaymentCost').val();
		data.tuitionCost        = $('#tuitionCost').val();
		data.degreeSchoolCost   = $('#degreeSchoolCost').val();
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	dataTable('#table_cost', 'costos de mensualidad');
	/**
	 * Fin Rutas Costos
	 */

	/**
	 * Estudiantes
	 */
	//Save Student
	$(document).off('click', '#saveStudent');
	$(document).on('click', '#saveStudent', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.fnameStudent           = $('#fnameStudent').val();
		data.snameStudent           = $('#snameStudent').val();
		data.flastStudent           = $('#flastStudent').val();
		data.slastStudent           = $('#slastStudent').val();
		data.sexStudent             = $('#sexStudent').val();
		data.phoneStudent           = $('#phoneStudent').val();
		data.emailsStudent           = $('#emailsStudent').val();
		data.addressStudent         = $('#addressStudent').val();
		data.degreeStudent          = $('#degreeStudent').val();
		data.discountTuitionStudent = $('#discountTuitionStudent').val();
		data.discountStudent        = $('#discountStudent').val();
		ajaxForm(url,'post',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Student
	$(document).off('click', '#updateStudent');
	$(document).on('click', '#updateStudent', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token                  = $('#fnameStudent').data('token');
		data.fnameStudent           = $('#fnameStudent').val();
		data.snameStudent           = $('#snameStudent').val();
		data.flastStudent           = $('#flastStudent').val();
		data.slastStudent           = $('#slastStudent').val();
		data.sexStudent             = $('#sexStudent').val();
		data.phoneStudent           = $('#phoneStudent').val();
		data.emailsStudent        		    = $('#emailsStudent').val();
		data.addressStudent         = $('#addressStudent').val();
		data.degreeStudent          = $('#degreeStudent').val();
		data.discountTuitionStudent = $('#discountTuitionStudent').val();
		data.statusStudent          = $('#statusStudent').val();
		data.discountStudent        = $('#discountStudent').val();
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Save Enrolled
	$(document).off('click', '#saveEnrolled');
	$(document).on('click', '#saveEnrolled', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/enrolled';
		ajaxForm(url, 'post', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

/*
	//Active Student
	$(document).off('click', '#activeStudent');
	$(document).on('click', '#activeStudent', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.Student_code').data('token');
		url        = $(this).data('url');
		url        = url + '/active/' + token;
		data.token = token;
		ajaxForm(url, 'patch', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Student
	$(document).off('click', '#deleteStudent');
	$(document).on('click', '#deleteStudent', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.Student_code').data('token');
		url        = $(this).data('url');
		url        = url + '/delete/' + token;
		data.token = token;
		ajaxForm(url, 'delete', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});*/

	dataTable('#table_student', 'estudiantes');

	dataTable('#table_student_enrolled', 'estudiantes matriculados');

	dataTable('#table_total_charges', 'total de cobros');

	/**
	 * Fin Estudiantes
	 */

	/**
	 * Asientos Auxiliares
	 */

	/**
	 * Save Detail Auxiliar Seat
	 */
	$(document).off('click', '#saveDetailAuxiliarySeat');
	$(document).on('click', '#saveDetailAuxiliarySeat', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/save';
		var data = {};
		data.dateAuxiliarySeat            = $('#dateAuxiliarySeat').val();
		data.typeSeatAuxiliarySeat        = $('#codeAuxiliarySeat').data('token');
		data.codeAuxiliarySeat            = $('#codeAuxiliarySeat').val();
		data.detailAuxiliarySeat          = $('#detailAuxiliarySeat').val();
		data.amountAuxiliarySeat          = $('#amountAuxiliarySeat').val();
		data.financialRecordAuxiliarySeat = $('#financialRecordAuxiliarySeat').val();
		data.accoutingPeriodAuxiliarySeat = $('#accoutingPeriodAuxiliarySeat').val();
		data.codeStudent				  = $("#financialRecordAuxiliarySeat option:selected").text();

        var type = $("#typeAuxiliarySeat").data('type')

        if( type == 'select') {
            data.textTypeAuxiliarySeat = $("#typeAuxiliarySeat option:selected").text();
            data.typeAuxiliarySeat     = $('#typeAuxiliarySeat').val();
        }else{
            data.textTypeAuxiliarySeat = $("#typeAuxiliarySeat").val();
            data.typeAuxiliarySeat     = $('#typeAuxiliarySeat').data('token');
        }
		ajaxForm(url,'post',data, null, 'true')
		.done( function (response) {

			if(response.success){
				$.unblockUI();
				var tr = addItemRow(data, response);
				if($('#table_auxiliar_seat_temp tbody tr:first').exists()){
					$('#table_auxiliar_seat_temp tbody').append(tr);
					$('#totalAuxiliarySeat').val(response.message.total);
				}else{
					$('#table_auxiliar_seat_temp').removeClass('hide');
					$('#table_auxiliar_seat_temp tbody').append(tr);
					var parent = $('#typeAuxiliarySeat').parent();
					$('#typeAuxiliarySeat').remove();
					addInputText(data, parent);
					$('#saveAuxiliarySeat').removeClass('hide');
					if(!$("#tokenAuxiliarySeat").exists()){
						var token = '<input id="tokenAuxiliarySeat" type="hidden" value="'+ response.message.token +'">';
						$("#table_auxiliar_seat_temp tbody").prepend(token);
					}
					$('#totalAuxiliarySeat').val(response.message.total);
				}
			}else{
				messageErrorAjax(response);
			}
		});
	});


	$(document).off('change', '#degreeStudentSeat');
	$(document).on('change', '#degreeStudentSeat', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/save';
		var data = {};

		data.dateAuxiliarySeat            = $('#dateAuxiliarySeat').val();
		data.typeSeatAuxiliarySeat        = $('#codeAuxiliarySeat').data('token');
		data.codeAuxiliarySeat            = $('#codeAuxiliarySeat').val();
		data.detailAuxiliarySeat          = "Mensualidad de ";
		data.amountAuxiliarySeat          = $('#amountAuxiliarySeat').val();
		data.degreeAuxiliarySeat          = $('#degreeStudentSeat').val();
		data.financialRecordAuxiliarySeat = $('#financialRecordAuxiliarySeat').val();
		data.accoutingPeriodAuxiliarySeat = $('#accoutingPeriodAuxiliarySeat').val();
		data.codeStudent				  = $("#financialRecordAuxiliarySeat option:selected").text();

        var type = $("#typeAuxiliarySeat").data('type')

        if( type == 'select') {
            data.textTypeAuxiliarySeat = $("#typeAuxiliarySeat option:selected").text();
            data.typeAuxiliarySeat     = $('#typeAuxiliarySeat').val();
        }else{
            data.textTypeAuxiliarySeat = $("#typeAuxiliarySeat").val();
            data.typeAuxiliarySeat     = $('#typeAuxiliarySeat').data('token');
        }
		ajaxForm(url,'post',data, null, 'true')
		.done( function (response) {

			if(response.success){
				$.unblockUI();
				var tr = addItemRow(data, response);
				if($('#table_auxiliar_seat_temp tbody tr:first').exists()){
					$('#table_auxiliar_seat_temp tbody').append(tr);
					$('#totalAuxiliarySeat').val(response.message.total);
				}else{
					$('#table_auxiliar_seat_temp').removeClass('hide');
					$('#table_auxiliar_seat_temp tbody').append(tr);
					var parent = $('#typeAuxiliarySeat').parent();
					$('#typeAuxiliarySeat').remove();
					addInputText(data, parent);
					$('#saveAuxiliarySeat').removeClass('hide');
					if(!$("#tokenAuxiliarySeat").exists()){
						var token = '<input id="tokenAuxiliarySeat" type="hidden" value="'+ response.message.token +'">';
						$("#table_auxiliar_seat_temp tbody").prepend(token);
					}
					$('#totalAuxiliarySeat').val(response.message.total);
				}
			}else{
				messageErrorAjax(response);
			}
		});
	});

	//Save Auxiliary Seat
	$(document).off('click', '#saveAuxiliarySeat');
	$(document).on('click', '#saveAuxiliarySeat', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/status';
		data.token = $('#tokenAuxiliarySeat').val();
		data.catalogId = $('#catalogAuxiliarySeat').val();

        if(data.textTypeAuxiliarySeat == 'Debito')
        {
            data.catalogAuxiliarySeat = $('#catalogAuxiliarySeat[data-type="debito"] option:selected').val();
        }else if(data.textTypeAuxiliarySeat == 'Credito'){
            data.catalogAuxiliarySeat = $('#catalogAuxiliarySeat[data-type="credito"] option:selected').val();
        }
		ajaxForm(url, 'post', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Auxiliary Set
	$(document).off('click', '#deleteDetailRow');
	$(document).on('click', '#deleteDetailRow', function(e){
		e.preventDefault();
		var row = $(this).parent().parent();
		var totalRows = $('#table_auxiliar_seat_temp tbody tr').length;
		idAuxiliarySeat  = $(this).data('id')
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
					$('#totalAuxiliarySeat').val(response.message.total);
				}
			}else{
				messageErrorAjax(response);
			}
		});
	});

	//Update Auxiliary Seat
	$(document).off('click', '#updateAuxiliarySeat');
	$(document).on('click', '#updateAuxiliarySeat', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token                        = $('#dateAuxiliarySeat').data('token');
		data.dateAuxiliarySeat            = $('#dateAuxiliarySeat').val();
		data.codeAuxiliarySeat            = $('#codeAuxiliarySeat').val();
		data.detailAuxiliarySeat          = $('#detailAuxiliarySeat').val();
		data.amountAuxiliarySeat          = $('#amountAuxiliarySeat').val();
		data.financialRecordAuxiliarySeat = $('#financialRecordAuxiliarySeat').val();
		data.accoutingPeriodAuxiliarySeat = $('#accoutingPeriodAuxiliarySeat').val();
		data.typeSeatAuxiliarySeat        = $('#typeSeatAuxiliarySeat').val();
		ajaxForm(url,'put',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	$(document).off('click', '#otherPeriods');
	$(document).on('click', '#otherPeriods', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/other';
		bootbox.confirm('¿Se aplicara un asiento a los estudiantes que no tenga el cobro del mes selecionado?', function (result) {
			if(result){
				var modal = '<div class="row"><div class="col-sm-4"><span class="pull-right" style="line-height:2.25em;">Seleccionar Mes:</span></div><div class="col-sm-6"><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span> <input id="dateOther" class="form-control" type="text"></div></div></div>';
				bootbox.dialog({
				  	message: modal,
				  	title: "Cobros a Estudiantes",
				  	animate: true,
				  	className: "my-modal-other",
				  	buttons: {
					    success: {
							label: "Grabar",
							className: "btn-success",
							callback: function() {
								var dateOther = $('#dateOther').val();
								data.dateOther = dateOther;
								ajaxForm(url,'post',data, null, 'true')
								.done( function (data) {
									messageAjax(data);
								});
							}
					    },
					    danger: {
					    	label: "Cancelar",
							className: "btn-default",
					    }
				  	}
				});
			}
		});
	});

	$(document).off('click', '#dateOther');
	$(document).on('click', '#dateOther', function(e){
		e.preventDefault();
		var today = new Date();
		var yyyy = today.getFullYear();
		var start = '01/'+ yyyy;
		var end = '12/'+ yyyy;
		var options = {
			language: 'es',
			startDate: start,
			endDate: end,
			orientation: 'top',
			format: 'mm/yyyy',
			minViewMode: 1,
			autoclose: true
		}
		$(this).datepicker(options).datepicker('show');
	});

/*
	//Active Student
	$(document).off('click', '#activeStudent');
	$(document).on('click', '#activeStudent', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.Student_code').data('token');
		url        = $(this).data('url');
		url        = url + '/active/' + token;
		data.token = token;
		ajaxForm(url, 'patch', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Delete Student
	$(document).off('click', '#deleteStudent');
	$(document).on('click', '#deleteStudent', function(e){
		e.preventDefault();
		var url;
		var token  = $(this).parent().parent().find('.Student_code').data('token');
		url        = $(this).data('url');
		url        = url + '/delete/' + token;
		data.token = token;
		ajaxForm(url, 'delete', data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});*/

	dataTable('#table_auxiliary_seat', 'asientos auxiliares');

	/**
	 * Fin Asientos Auxiliares
	 */

	/**
	 * Tipos de Asientos
	 */

	//Save TypeSeat
	$(document).off('click', '#saveTypeSeat');
	$(document).on('click', '#saveTypeSeat', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.abbreviationTypeSeat = $('#abbreviationTypeSeat').val();
		data.nameTypeSeat         = $('#nameTypeSeat').val();
		data.quatityTypeSeat      = $('#quatityTypeSeat').val();
		data.yearTypeSeat         = $('#yearTypeSeat').val();
		ajaxForm(url,'post',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});
	dataTable('#table_type_seat','tipos de asientos');

	/**
	 * Fin tipos de Asientos
	 */
	/**
	 * Receipts
	 */
	// Save AuxiliaryReceipt

	/**
	 * Suppliers
	 */
		//Save Supplier
	$(document).off('click', '#saveProveedor');
	$(document).on('click', '#saveProveedor', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = 'institucion/inst/'+ url + '/save';
		data.nameSupplier   		= $('#nameSupplier').val();
		data.charterSupplier    	= $('#charterSupplier').val();
		data.codeSupplier    		= $('#codeSupplier').val();
		data.amountSupplier    		= $('#amountSupplier').val();
		data.addressSupplier    	= $('#addressSupplier').val();
		data.phoneSupplier   		= $('#phoneSupplier').val();
		data.emailSupplier   		= $('#emailSupplier').val();
		data.contactSupplier   		= $('#contactSupplier').val();
		data.phoneContactSupplier   = $('#phoneContactSupplier').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	dataTable('#table_supplier', 'proveedores');


	//update Supplier
	$(document).off('click', '#updateProveedor');
	$(document).on('click', '#updateProveedor', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = 'institucion/inst/'+ url + '/update';

		data.nameSupplier   		= $('#nameSupplier').val();
		data.token    				= $('#nameSupplier').data('token');
		data.charterSupplier    	= $('#charterSupplier').val();
		data.amountSupplier    		= $('#amountSupplier').val();
		data.codeSupplier    		= $('#codeSupplier').val();
		data.addressSupplier    	= $('#addressSupplier').val();
		data.phoneSupplier   		= $('#phoneSupplier').val();
		data.emailSupplier   		= $('#emailSupplier').val();
		data.contactSupplier   		= $('#contactSupplier').val();
		data.phoneContactSupplier   = $('#phoneContactSupplier').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	/**
	 * Catalogs
	 */

	//Search level
	$(document).off('change', '#levelCatalog');
	$(document).on('change', '#levelCatalog', function(){
		var level = $(this).val();
		data.level = level;
		$.ajax({
			url: '/' + 'institucion/inst/catalogos/level',
		    type: 'post',
		    data: {data: JSON.stringify(data)},
		    datatype: 'json',
		    beforeSend: function(){
	    		$('#groupCatalog').prop('disabled', true);
		    },
		    error:function(){
		    	bootbox.alert("<p class='red'>No se pueden cargar los grupos de catalogos.</p>")
		    }
		}).done( function (response){
			$('#groupCatalog').empty();
			$('#groupCatalog').html(response);
			$('#groupCatalog').prop('disabled', false);
		});
	});

	//Save Catalogs
	$(document).off('click', '#saveCatalog');
	$(document).on('click', '#saveCatalog', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = url + '/save';
		data.nameCatalog  = $('#nameCatalog').val();
		data.styleCatalog = $('#styleCatalog').val();
		data.noteCatalog  = $('#noteCatalog').bootstrapSwitch('state');
		data.typeCatalog  = $('#typeCatalog').val();
		data.levelCatalog = $('#levelCatalog').val();
		data.groupCatalog = $('#groupCatalog').val();
		ajaxForm(url,'post',data, null, 'true')
		.done( function (data) {
			messageAjax(data);
		});
	});

	//Update Catalogs
	$(document).off('click', '#updateCatalog');
	$(document).on('click', '#updateCatalog', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/update';
		data.token = $('#codeCatalog').data('token');
		data.nameCatalog  = $('#nameCatalog').val();
		data.noteCatalog  = $('#noteCatalog').bootstrapSwitch('state');
		ajaxForm(url, 'put', data, null, 'true')
		.done(function(response){
			messageAjax(response);
		});
	});


	// Estado de cuentas del catalogo

	/* Update Menu Restaurant */
	$(document).off('click', '#searchCatalog');
	$(document).on('click', '#searchCatalog', function(e){
		e.preventDefault();
		url = $(this).data('url');
		data.nameCatalogs  = $('#nameCatalogs').val();
		data.monthInCatalogs  = $('#monthInCatalogs').val();
		data.monthOutCatalogs  = $('#monthOutCatalogs').val();
		url = 'institucion/inst/' + url + '/estado-de-cuenta-de-catalogo';
		ajaxForm(url,'post',data)
		.done( function (data) {
			//messageAjax(data);
			$.unblockUI();
			window.open('/'+url);
		});
	});
	dataTable('#table_catalogs', 'catálogos');

	/**
	 * Seatings
	 */

	//Save Seating
	$(document).off('click', '#saveDetailSeating');
	$(document).on('click', '#saveDetailSeating', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/save';
		var accountPartSeating  = [];
		var amountSeating  = [];
		var textAccountPartSeating = [];
		$(".accountPartSeating").each(function(index,value){
			accountPartSeating[index]     = $(this).val();
			textAccountPartSeating[index] = $("option:selected", this).text();
		});
		$(".amountSeating").each(function(index,value){
		    amountSeating[index] = $(this).val();
		});
		data.accoutingPeriodSeating = $('#accoutingPeriodSeating').data('token');
		data.dateSeating            = $('#dateSeating').val();
		data.codeSeating            = $('#typeSeatSeating').val();
        data.typeSeatSeating        = $('#typeSeatSeating').data('token');
        data.accountSeating         = $('#accountSeating').val();
		data.detailSeating          = $('#detailSeating').val();
		data.amountSeating          = amountSeating;
		data.typeSeating            = $('#typeSeating').val();
		data.accountPartSeating     = accountPartSeating;
		data.tokenSeating           = $('#tokenSeating').val();
		data.tokenSeating1           = $('#tokenSeating1').val();
		data.textAccountSeating     = $("#accountSeating option:selected").text();
		data.textTypeSeating        = $('#typeSeating option:selected').text();
		data.textAccountPartSeating = textAccountPartSeating;
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response){
			if(response.success){
				$.unblockUI();
				var tr = addItemSeat(data, response);
				if($('#table_seating_temp tbody tr:first').exists()){
					$('#table_seating_temp tbody tr:first').before(tr);
					$('#totalSeating').val(response.message.total);
				}else{
					$('#table_seating_temp').removeClass('hide');
					$('#table_seating_temp tbody').append(tr);
					$('#saveSeating').removeClass('hide');
					if(!$("#tokenSeating").exists()){
						var token = '<input id="tokenSeating" type="hidden" value="'+ response.message.token +'">';
						$("#table_seating_temp tbody").prepend(token);
					}
					$('#totalSeating').val(response.message.total);
				}
			}else{
				messageErrorAjax(response);
			}
		});
	});

	dataTable('#table_seatings', 'asientos');

    //Save Seating
    $(document).off('click', '#saveSeating');
    $(document).on('click', '#saveSeating', function(e){
        e.preventDefault();
        url = $(this).data('url');
        url = url + '/status';
        data.token = $('#tokenSeating').val();
        data.token1 = $('#tokenSeating1').val();
        ajaxForm(url, 'post', data, null, 'true')
        .done( function (data) {
            messageAjax(data);
        });
    });

    //Delete Seating
    $(document).off('click', '#deleteDetailSeating');
    $(document).on('click', '#deleteDetailSeating', function(e){
        e.preventDefault();
		var row       = $(this).parent().parent();
		var totalRows = $('#table_seating_temp tbody tr').length;
		var idSeating = $(this).data('id')
		var url       = $(this).data('url');
		var dataClass = $(this).data('class');
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
                	$('.'+dataClass).remove();
                    row.remove();
                    bootbox.alert('<p class="success-ajax">'+response.message.message+'</p>');
                    $('#totalSeating').val(response.message.total);
                }
            }else{
                messageErrorAjax(response);
            }
        });
    });

	/**
	 *
	 */
	/**
	 * Checks
	 */

		//Save Seating
	$(document).off('click', '#saveDetailSeating');
	$(document).on('click', '#saveDetailSeating', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		url = url + '/save';
		var accountPartSeating  = [];
		var amountSeating  = [];
		var textAccountPartSeating = [];
		$(".accountPartSeating").each(function(index,value){
			accountPartSeating[index]     = $(this).val();
			textAccountPartSeating[index] = $("option:selected", this).text();
		});
		$(".amountSeating").each(function(index,value){
			amountSeating[index] = $(this).val();
		});
		data.accoutingPeriodSeating = $('#accoutingPeriodSeating').data('token');
		data.dateSeating            = $('#dateSeating').val();
		data.codeSeating            = $('#typeSeatSeating').val();
		data.typeSeatSeating        = $('#typeSeatSeating').data('token');
		data.accountSeating         = $('#accountSeating').val();
		data.detailSeating          = $('#detailSeating').val();
		data.amountSeating          = amountSeating;
		data.typeSeating            = $('#typeSeating').val();
		data.accountPartSeating     = accountPartSeating;
		data.tokenSeating           = $('#tokenSeating').val();
		data.textAccountSeating     = $("#accountSeating option:selected").text();
		data.textTypeSeating        = $('#typeSeating option:selected').text();
		data.textAccountPartSeating = textAccountPartSeating;
		ajaxForm(url, 'post', data, null, 'true')
			.done(function (response){
				if(response.success){
					$.unblockUI();
					var tr = addItemSeat(data, response);
					if($('#table_seating_temp tbody tr:first').exists()){
						$('#table_seating_temp tbody tr:first').before(tr);
						$('#totalSeating').val(response.message.total);
					}else{
						$('#table_seating_temp').removeClass('hide');
						$('#table_seating_temp tbody').append(tr);
						$('#saveSeating').removeClass('hide');
						if(!$("#tokenSeating").exists()){
							var token = '<input id="tokenSeating" type="hidden" value="'+ response.message.token +'">';
							$("#table_seating_temp tbody").prepend(token);
						}
						$('#totalSeating').val(response.message.total);
					}
				}else{
					messageErrorAjax(response);
				}
			});
	});


	/**
	 * Roles
	 */
	/**
	 * Roles
	 */

	$(document).off('click', '#updateRole');
	$(document).on('click', '#updateRole', function(e){
		e.preventDefault();
		var url;
		var idMenu;
		var roles = [];
		url = $(this).data('url');
		url = url + '/update';

		$('.menu-role').each(function (index) {
			idMenu = $(this).attr('data-menu');
			var idTasks     = [];
			var statusTasks = [];
			$('.menu-role:eq('+index+') .role-checkbox').each(function (i){
				idTasks[i]     = $(this).data('id');
				statusTasks[i] = $(this).bootstrapSwitch('state');
			});
			roles[idMenu] = {'idTasks': idTasks, 'statusTasks': statusTasks};
		});
		data.idUser = $("#idUser").val();
		data.roles  = roles;
		ajaxForm(url,'put',data)
		.done( function (response) {
			messageAjax(response);
		});
	});

	dataTable('#table_role', 'roles');

	/**
	 * End Roles
	 */

	/**
	 * Periodos Accounting
	 */

	$(document).off('click', '#saveAccountingPeriod');
	$(document).on('click', '#saveAccountingPeriod', function(e){
		var that = $(this);
		bootbox.confirm("¿Esta seguro de generar el nuevo Periodo Contable?, recuerde que no se puede regresar a un periodo anterior, verifique que tenga los saldos correctos.", function(result) {
		 	if(result){
		 		bootbox.prompt({
			        title: "Ingrese su clave",
			        inputType: "password",
			        callback: function(result) {
			            if(result) {
							var url = that.data('url');
					 		url = url + '/save';
					 		data.clave = result;
					 		ajaxForm(url, 'post', data, null, 'true')
					 		.done( function (response){
					 			messageAjax(response);
					 		});
						} else {
							bootbox.alert('No ha ingresado su Clave');
						}
			        }
			    });
		 	}
		});
	});

  $(document).off('click', '#GenerarEstadoResultado');
  $(document).on('click', '#GenerarEstadoResultado', function(e){
    var that = $(this);




              var url = that.data('url');
              url = 'reportes/' + url ;
              data.year = $("#year").val();
              ajaxForm(url, 'post', data, null, 'true')
                .done( function (response){

                });





  });
	/**
	 * Cortes de Caja
	 */
	$(document).off('click', '#saveCourtCase');
	$(document).on('click', '#saveCourtCase', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/save';
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response) {
			if(response.success){
				var tokenCourtCase = response.message;
				$.unblockUI();
				bootbox.alert('<p class="success-ajax">Se registro el corte de caja con éxito.</p>', function(){
					window.open('/institucion/inst/cortes-de-caja/impresion/'+tokenCourtCase+'/'+1, 'one');
					window.open('/institucion/inst/cortes-de-caja/impresion/'+tokenCourtCase+'/'+2, 'two');
					window.open('/institucion/inst/cortes-de-caja/impresion/'+tokenCourtCase+'/'+3, 'tree');
					location.reload();
				});
			}else{
				messageErrorAjax(response);
			}
		});
	});

	dataTable('#table_courtCase', 'cortes de caja');

	/**
	 * Configuracion
	 */
	$(document).off('click', '#saveSetting');
	$(document).on('click', '#saveSetting', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/save';
		data.typeSeatSetting = $('#typeSeatSetting').data('token');
		data.catalogSetting  = $('#catalogSetting').val();
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response) {
			messageAjax(response);
		});
	});

	// Update
	$(document).off('click', '#updateSetting');
	$(document).on('click', '#updateSetting', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/update';
		data.token           = $('#catalogSetting').data('token');
		data.typeSeatSetting = $('#typeSeatSetting').data('token');
		data.catalogSetting  = $('#catalogSetting').val();
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response) {
			messageAjax(response);
		});
	});

	dataTable('#table_settings','configuraciones');

	//Add partSeating
	$(document).off('click', '#addPartSeating');
	$(document).on('click', '#addPartSeating', function(e){
		e.preventDefault();
		if($('.accountPart aside').length == 1){
			$('#removePartSeating').removeClass('hide');
		}
		var partSeating = $('.accountPart aside:first').clone(true,true);
		partSeating.appendTo('.accountPart');
	});

	//Delete partSeating
	$(document).on('click', '#removePartSeating', function(e){
		e.preventDefault();
		var element = $(this);
		var partSeating = $('.accountPart aside:first');
		partSeating.remove();
		if($('.accountPart aside').length == 1){
			element.addClass('hide');
		}
	});

	// Add partSeatingCheck
	$(document).off('click', '#addPartCheck');
	$(document).on('click', '#addPartCheck', function(e){
		e.preventDefault();
		if($('.accountPartCheckContainer aside').length == 1){
			$('#removePartCheck').removeClass('hide');
		}
		var partSeating = $('.accountPartCheckContainer aside:first').clone(true,true);
		partSeating.appendTo('.accountPartCheckContainer');
		totalCheck();
	});

	// Delete partSeatingCheck
	$(document).off('click', '#removePartCheck');
	$(document).on('click', '#removePartCheck', function(e){
		e.preventDefault();
		var element = $(this);
		var partSeating = $('.accountPartCheckContainer aside:first');
		partSeating.remove();
		if($('.accountPartCheckContainer aside').length == 1){
			element.addClass('hide');
		}
		totalCheck();
	});

	// Sum total Amount Check
	$(document).off('blur', '.amountCheck');
	$(document).on('blur', '.amountCheck', function(e){
		var that       = $(this);
		totalCheck();
	});

	function totalCheck(){
		var totalCheck = $('#totalCheck');
		var total = 0;
		$(".amountCheck").each(function() {
			total += parseFloat($(this).val());
		});
		totalCheck.val(total);
	}

	$(document).off('click', '#saveCheck');
	$(document).on('click', '#saveCheck', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/save';

		var accountPartCheck = [];
		var amountCheck = [];
		var detailCheck = [];
		$(".accountPartCheck").each(function(index,value){
		    accountPartCheck[index] = $(this).val();
		});
		$(".amountCheck").each(function(index,value){
		    amountCheck[index] = $(this).val();
		});
		$(".detailCheck").each(function(index,value){
		    detailCheck[index] = $(this).val();
		});

		data.accoutingPeriodCheck = $('#accoutingPeriodCheck').data('token');
		data.tokenTypeSeatCheck   = $('#codeCheck').data('token');
		data.codeCheck        	  = $('#codeCheck').val();
		data.dateCheck        	  = $('#dateCheck').val();
		data.paguesenCheck        = $('#paguesenCheck').val();
		data.accountCheck         = $('#accountCheck').val();
		data.accountPartCheck     = accountPartCheck;
		data.amountCheck          = amountCheck;
		data.detailCheck          = detailCheck;
		data.anularCheck 		  = '';
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response) {
			messageAjax(response);
		});
	});

	$(document).off('click', '#anularCheck');
	$(document).on('click', '#anularCheck', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/anular';
		if(confirm('Realmente desea Anular el Recibo')) {
			var accountPartCheck = [];
			var amountCheck = [];
			var detailCheck = [];
			$(".accountPartCheck").each(function (index, value) {
				accountPartCheck[index] = $(this).val();
			});
			$(".amountCheck").each(function (index, value) {
				amountCheck[index] = $(this).val();
			});
			$(".detailCheck").each(function (index, value) {
				detailCheck[index] = $(this).val();
			});

			data.accoutingPeriodCheck = $('#accoutingPeriodCheck').data('token');
			data.tokenTypeSeatCheck = $('#codeCheck').data('token');
			data.codeCheck = $('#codeCheck').val();
			data.dateCheck = $('#dateCheck').val();
			data.paguesenCheck = 'Cheque Anulado';
			data.accountCheck = $('#accountCheck').val();
			data.accountPartCheck = accountPartCheck;
			data.amountCheck = amountCheck;
			data.detailCheck = 'Anulado';
			data.anularCheck = 'Anulado';

			ajaxForm(url, 'post', data, null, 'true')
				.done(function (response) {
					messageAjax(response);
				});
		}
	});
	// Datepicker all inputs
	if($('.datepicker').exists())
	{
		$('.datepicker').datepicker({
			language: 'es',
		    format: "yyyy/mm/dd",
		});
	}

	// Equal Height - form buy
	if( $('#form-buy').exists() ){
		$('#form-buy .col-sm-4').matchHeight();
	}

	// Change typeBuy
	$("input[name='typeBuy']").on("change", function(e){
		var self = $(this);

		if(self.val() == "1")
		{
			$('.cuentaTransferencia').removeClass('hide');
			$('.dateExpiration').addClass('hide');
		}
		else if(self.val() == "2")
		{
			$('.dateExpiration').removeClass('hide');
			$('.cuentaTransferencia').addClass('hide');
		}
		else
		{
			$('.dateExpiration').addClass('hide');
			$('.cuentaTransferencia').addClass('hide');
		}
		$('#form-buy .col-sm-4').matchHeight();
	});

	// Change typeInvoice
	$("input[name='typeInvoice']").on("change", function(e){
		var self = $(this);
		var $ivaBuy = $('#ivaBuy');
		var $discountBuy = $('#discountBuy');
		var $totalBuy = $('#totalBuy');
		// Type Ivi
		if(self.val() == "1")
		{
			$('.notIvi').val('').attr('disabled', true);
			$('#totalBuy').val('').attr('disabled', false).focus();
		// Type Iva
		}else if(self.val() == "2"){
			$('.notIvi').val('0.00').attr('disabled', true);
			$ivaBuy.val('').attr('disabled', false).focus();
			$discountBuy.val('').attr('disabled', false);
			$totalBuy.val('').attr('disabled', false);
		// Type Detail
		}else{
			var total = totalBuy();
			$('.notIvi').val('').attr('disabled', false);
			$('#totalBuy').val(total.toFixed(2)).attr('disabled', true);
			$('.notIvi:first').focus();
		}
		$('#form-buy .col-sm-4').matchHeight();
	});

	// Total NotIvi
	$(document).off("blur", ".notIvi");
	$(document).on("blur", ".notIvi", function(e){
		// Type Detallado
		if($("input[name='typeInvoice']:checked").val() == 0)
		{
			var total = totalBuy();
			$('#totalBuy').val(total.toFixed(2));
		// Type IVA
		}else if($("input[name='typeInvoice']:checked").val() == 2){
			var $ivaBuy = $('#ivaBuy');
			if( parseFloat($ivaBuy.val()) > 0 )
			{
				var gravado = $ivaBuy.val() / 0.13;
				$('#totalGravadoBuy').val(gravado.toFixed(2));


			}else{
				$('#totalGravadoBuy').val('');
			}

			var total = totalExcento();
			console.log(total);
			if(total > 0)
			{
				$('#totalExcentoBuy').val(total.toFixed(2));
			}else{
				$('#totalExcentoBuy').val('0.00');
			}
		}
	});

	// Total disccount
	$(document).off("blur", "#discountBuy");
	$(document).on("blur", "#discountBuy", function(e){
		if( $("input[name='typeInvoice']:checked").val() == 0 )
		{
			var total = totalBuy();
			$('#totalBuy').val(total.toFixed(2));
		}
		else if( $("input[name='typeInvoice']:checked").val() == 2 ) {
			var $exento = $('#totalExcentoBuy').val();
			var $gravado = $('#totalGravadoBuy').val();
			var $discount = $('#discountBuy').val();
			var $subsidized = $('#subsidizedBuy').val();
			var $ivaBuy = $('#ivaBuy').val();
			if (parseFloat($discount) > 0){
				var total = parseFloat($gravado) + parseFloat($discount);
				$('#totalGravadoBuy').val(total.toFixed(2));
				var totalbuy = (parseFloat($gravado) + parseFloat($ivaBuy) + parseFloat($exento))-( parseFloat($discount) ) ;
				$('#totalBuy').val(totalbuy.toFixed(2));
			}
		}
	});

	// Total subsidized
	$(document).off("blur", "#subsidizedBuy");
	$(document).on("blur", "#subsidizedBuy", function(e){
		if( $("input[name='typeInvoice']:checked").val() == 2 ) {
			var $exento = $('#totalExcentoBuy').val();
			var $gravado = $('#totalGravadoBuy').val();
			var $discount = $('#discountBuy').val();
			var $subsidized = $('#subsidizedBuy').val();
			var $ivaBuy = $('#ivaBuy').val();
			if (parseFloat($subsidized) > 0){
				var total = (parseFloat($gravado) + parseFloat($ivaBuy) + parseFloat($exento))-( parseFloat($discount)+parseFloat($subsidized) ) ;
				$('#totalBuy').val(total.toFixed(2));
			}
		}
	});

	$(document).off('blur', '#totalBuy');
	$(document).on('blur', '#totalBuy', function(e){
		var self = $(this);
		var totalGravadoBuy = 0;
		var ivaBuy = 0;
		if( parseFloat(self.val()) > 0 )
		{

			if( $("input[name='typeInvoice']:checked").val() == 1 )
			{
				totalGravadoBuy = parseFloat(self.val()) / 1.13;
				ivaBuy = parseFloat(self.val()) - totalGravadoBuy;
				$('#totalGravadoBuy').val(totalGravadoBuy.toFixed(2));
				$('#ivaBuy').val(ivaBuy.toFixed(2));
			}else if( $("input[name='typeInvoice']:checked").val() == 2 ){
				var total = totalExcento();
				console.log(total);
				if(total > 0)
				{
					$('#totalExcentoBuy').val(total.toFixed(2));
				}else{
					$('#totalExcentoBuy').val('');
				}
			}
		}else{
			if( $("input[name='typeInvoice']:checked").val() == 0 )
			{
				$('#totalGravadoBuy').val('');
				$('#ivaBuy').val('');
			}else if( $("input[name='typeInvoice']:checked").val() == 2 ){
				var total = totalExcento();
				console.log(total);
				if(total > 0)
				{
					$('#totalExcentoBuy').val(total.toFixed(2));
				}else{
					$('#totalExcentoBuy').val('');
				}
			}
		}
	});

	function totalBuy(){
		var tot = parseFloat(0);
		$(".notIvi").each(function(index,value){
			if( parseFloat($(this).val()) >= 0)
			{
				tot += parseFloat($(this).val());
			}
		});
		if( parseFloat($('#discountBuy').val()) >= 0 )
		{
			tot = tot - parseFloat($('#discountBuy').val());
		}
		return tot;
	}

	function totalExcento(){
		var tot = parseFloat(0);
		var $totalBuy = $('#totalBuy');
		var $totalGravado = $('#totalGravadoBuy');
		var $totaliva = $('#ivaBuy');
		if($totalBuy.val() > 0)
		{
			tot += parseFloat($totalBuy.val());
		}
		if($totalGravado.val() > 0)
		{
			tot -= parseFloat($totalGravado.val());
		}
		if($totaliva.val() > 0)
		{
			tot -= parseFloat($totaliva.val());
		}
		return tot;
	}

	$(document).off('click', '#saveBuy');
	$(document).on('click', '#saveBuy', function(e){
		e.preventDefault();
		var url;
		url = $(this).data('url');
		url = url + '/save';

		data.accoutingPeriodBuy = $('#accoutingPeriodBuy').data('token');
		data.dateBuy            = $('#dateBuy').val();
		data.typeSeatBuy        = $('#typeSeatBuy').val();
		data.tokenTypeSeatBuy   = $('#typeSeatBuy').data('token');
		data.supplierBuy        = $('#supplierBuy').val();
		data.typeBuy            = $('input[name="typeBuy"]:checked').val();
		data.dateExpirationBuy  = $("#dateExpirationBuy").val();
		data.referenceBuy       = $("#referenceBuy").val();
		data.typeInvoice        = $('input[name="typeInvoice"]:checked').val();
		data.totalGravadoBuy    = $('#totalGravadoBuy').val();
		data.totalExcentoBuy    = $('#totalExcentoBuy').val();
		data.ivaBuy             = $('#ivaBuy').val();
		data.otherBuy           = $('#otherBuy').val()
		data.discountBuy        = $('#discountBuy').val();
		data.subsidizedBuy        = $('#subsidizedBuy').val();
		data.totalBuy           = $('#totalBuy').val();
		data.transfBuy          = $('#transfBuy').val();
		ajaxForm(url, 'post', data, null, 'true')
		.done(function (response) {
			messageAjax(response);
		});
	});

	$(document).off('change', '#typeAuxiliarySeat');
	$(document).on('change', '#typeAuxiliarySeat', function(e){
        var that = $(this);
        var $credito = $('#catalogAuxiliarySeatC[data-type="credito"]');
        var $debito = $('#catalogAuxiliarySeatD[data-type="debito"]');
        alert(that.find('option:selected').text());
        if(that.data('url') == 'asientos-auxiliares')
        {
            if(that.find('option:selected').text() == 'Credito')
            {
                $("#ctaContaC").removeClass('ocultar');
                $("#ctaContaD").addClass('ocultar');
            }else{
                $("#ctaContaD").removeClass('ocultar');
                $("#ctaContaC").addClass('ocultar');
            }
        }
	});


	/**
	 * Marcas
	 */

	/* Save Marcas*/
	$(document).off('click', '#saveBrand');
	$(document).on('click', '#saveBrand', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameBrand       = $('#nameBrand').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	/* update Marcas*/
	$(document).off('click', '#updateBrand');
	$(document).on('click', '#updateBrand', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameBrand       = $('#nameBrand').val();
		data.token       	 = $('#nameBrand').data('token');
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});
	dataTable('#table_brands', 'Marcas');

	/**
	 *  Productos Crudos
	 */

	/* Save Producto Crudos*/
	$(document).off('click', '#saveRawProduct');
	$(document).on('click', '#saveRawProduct', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.codeRawProduct       	= $('#codeRawProduct').val();
		data.descriptionRawProduct  = $('#descriptionRawProduct').val();
		data.typeRawProduct       	= $('#typeRawProduct').val();
		data.unitsRawProduct       	= $('#unitsRawProduct').val();
		data.brand_idRawProduct     = $('#brandRawProduct').val();
		data.priceRawProduct     	= $('#priceRawProduct').val();
		data.cocidoRawProduct       = $('#cocidoRawProduct').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});
/*	$(document).on('change','#cocidoRawProduct',function (e) {

        var status =  $(this).bootstrapSwitch('state');

        if(status==true){
            $('#priceRawProduct').css('visibility','hidden');
		}
		else{
            $('#priceRawProduct').css('visibility','visible');
		}
    });*/
	/* Update Producto Crudos*/
	$(document).off('click', '#updateRawProduct');
	$(document).on('click', '#updateRawProduct', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.codeRawProduct       	= $('#codeRawProduct').val();
		data.token       			= $('#codeRawProduct').data('token');
		data.descriptionRawProduct  = $('#descriptionRawProduct').val();
		data.unitsRawProduct       	= $('#unitsRawProduct').val();
		data.typeRawProduct       	= $('#typeRawProduct').val();
		data.brand_idRawProduct     = $('#brandRawProduct').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});
	dataTable('#table_rawProduct', 'Productos Crudos');

	// Add row in Inventories incomes
	$(document).off('click', '.Inventories #add');
	$(document).on('click', '.Inventories #add', function(e){
		e.preventDefault();
		var urlTpl = getTpl('inventories/incomes', 'create');
		//console.log(urlTpl);
		$.get(urlTpl, function(html){
			var $TableDetail = $('.Inventories .TableDetail');
			$TableDetail.find('#products').prepend(html).children(':first').hide().fadeIn('slow',function(){
				//calculateTotal('in');
			});
			//console.log($products);
		});
	});

	// Delete row Inventories incomes
	$(document).off('click','.Inventories .TableDetail .delete');
	$(document).on('click','.Inventories .TableDetail .delete', function(e){
		e.preventDefault();
		$(this).parent().parent().fadeOut('slow',function(){
			this.remove();
			calculateTotal('out');
		});
	});

	// CalculeTotal Inventories incomes
	var calculateTotal = function(type){
		var $totalIncome = $('#totalIncome');
		var $TableDetail = $('.TableDetail');
		var total_items = $TableDetail.find('.item').length;
		var $subtotal_gravado = $TableDetail.find('.subtotal_gravado');
		var $subtotal_excento = $TableDetail.find('.subtotal_excento');
		var $subtotal = $TableDetail.find('.subtotal');
		var $iva = $TableDetail.find('#iva');
		var $discount_total = $TableDetail.find('#discount_total');
		var $total = $TableDetail.find('#total');
		var $reset = $TableDetail.find('.reset');
		var total_excento = 0;
		var total_gravado = 0;
		var subtotal = 0;
		var tot_iva = 0;
		var discount = 0;
		var total;

		if(total_items == 0)
		{
			resetTotal();
		}else{
			for(var i = 0; i < total_items; i++)
			{
				var row = $('.TableDetail .item').eq(i);
				var amount = parseInt(row.find('.amount').text());
				var cost = parseFloat(row.find('.cost').text());
				var disc = parseFloat(row.find('.discount').text());
				if(row.find('.type').text() == "E")
				{
					if(parseFloat(row.find('.total').text()) > 0)
					{
						total_excento += parseFloat(row.find('.total').text());
						discount +=  (amount * cost * disc) / 100 ;
					}else{
						total_excento += 0;
						discount += 0;
					}
				}else{
					if(parseFloat(row.find('.total').text()) > 0)
					{
						total_gravado += parseFloat(row.find('.total').text());
						discount +=  (amount * cost * disc) / 100 ;
					}else{
						total_gravado += 0;
						discount += 0;
					}
				}
				if(i == total_items - 1)
				{
					subtotal = total_excento + total_gravado;
					tot_iva  = subtotal * iva;
					total    = subtotal + tot_iva;
					$subtotal_gravado.html(total_gravado.toFixed(2));
					$subtotal_excento.html(total_excento.toFixed(2));
					$subtotal.html(subtotal.toFixed(2));
					$iva.html(tot_iva.toFixed(2));
					$total.html(total.toFixed(2));
					$discount_total.html(discount.toFixed(2));
					$totalIncome.val(total.toFixed(2));
				}
			}
		}
	};

	var resetTotal = function(){
		$('.TableDetail .reset').html('0.00');
		$('#totalIncome').val('0.00');
	}



	$(document).off('change', '#rawProductRecipes');
	$(document).on('change', '#rawProductRecipes', function(e){
		e.preventDefault();
		 url = $(this).data('url');
			 url = 'institucion/inst/' + url ;
		 data.token = $('#rawProductRecipes').val();

			ajaxForm(url,'post',data)
				.done( function (data) {
					$.unblockUI();
					$('#select2-unitsRecipes-container').text("");
					$('#unitsRecipes').empty();

					$.each(data, function(index, unit) {
						$('#unitsRecipes').append("<option value="+ unit +">" + unit + "</option>");
					});

					$("#unitsRecipes option:first").attr('selected','selected');
					$('#select2-unitsRecipes-container').text($("#unitsRecipes option:first").val());
			});
	});

	/* Delete Component of Menu Element */
	$(document).off('click', '#delete_recipt');
	$(document).on('click', '#delete_recipt', function(e){
		e.preventDefault();
		data.tokenCookedProduct = $(this).attr('data-token');
		data.delete = true;
		url = $(this).data('url');
		url = 'institucion/inst/delete/' + url ;
		ajaxForm(url, 'post', data)
			.done(function(data){
				messageAjax(data);
			});


	});

	/* reporte de processedProduct */
	$(document).off('click', '#recordSaleCookedProduct');
	$(document).on('click', '#recordSaleCookedProduct', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/report';
		data.dateInCookedProduct  = $('#dateInCookedProduct').val();
		data.dateOutCookedProduct  = $('#dateOutCookedProduct').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				$.unblockUI();
				window.open('/'+url);
			});
	});

	/* reporte de MenuRestaurant */
	$(document).off('click', '#recordSaleMenuRestaurant');
	$(document).on('click', '#recordSaleMenuRestaurant', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/report';
		data.dateInMenuRestaurant  = $('#dateInMenuRestaurant').val();
		data.dateOutMenuRestaurant = $('#dateOutMenuRestaurant').val();
		data.receiptMenuRestaurant = $('#receiptMenuRestaurant').bootstrapSwitch('state');
		data.saleMenuRestaurant = $('#saleMenuRestaurant').bootstrapSwitch('state');
		data.menuMenuRestaurant = $('#menuMenuRestaurant').bootstrapSwitch('state');

		ajaxForm(url,'post',data)
			.done( function (data) {
				$.unblockUI();
				window.open('/'+url);
			});
	});

	dataTable('#table_supplier_brands', 'Marcas');
	dataTable('#table_inventaryIncome', 'Ingresos de Inventario');


	/* update Marcas*/
	$(document).off('click', '#updateBrand');
	$(document).on('click', '#updateBrand', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameBrand = $('#nameBrand').val();
		data.token     = $('#nameBrand').data('token');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	/* save ProveedorMarcas*/
	$(document).off('click', '#saveProveedorMarcas');
	$(document).on('click', '#saveProveedorMarcas', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/marcas/save';
		data.tokenSupplier = $('#tokenSupplier').val();
		data.tokenBrand = $('#tokenBrand').val();
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});
	/* save ProveedorMarcas*/
	$(document).off('click', '#eliminar-menu-restaurante');
	$(document).on('click', '#eliminar-menu-restaurante', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/eliminar';
		data.token = $(this).data('token');;
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});
	$(document).off('click', '#delete_brand');
	$(document).on('click', '#delete_brand', function(e){
		e.preventDefault();
		data.tokenBrand = $(this).attr('data-token');
		data.tokenSupplier = $('#tokenSupplier').val();
		data.delete = true;
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/marcas/save';
		ajaxForm(url, 'post', data)
		.done(function(data){
			messageAjax(data);
		});
	});

	$(document).on('change', '#supplierIncome', function(e){
		e.preventDefault();
		data.tokenSupplier = $(this).val();
		var url = 'institucion/inst/ingresos-inventario/supplier';
		ajaxForm(url,'post',data,'Buscando productos')
		.done(function(response){
			messageAjax(response, 'no_bootbox');
			data.products_income = response.message;
		});
	});

	if($('#supplierIncome').exists()){
		data.tokenSupplier = $('#supplierIncome').val();
		var url = 'supplier';
		$.post(url,{data: JSON.stringify(data)})
		.done(function(response){
			data.products_income = response.message;
		});
	}

	var autocomplete = function(selector, data){
		selector.autocomplete({
		  	minLength: 1,
		  	source: function(request, response){
		  		var matcher = $.ui.autocomplete.filter(data, $.trim(request.term));
		  		if(matcher.length == 0)
		  		{
			  		if(data.length > 0)
			  		{
			  			matcher = ["Sin resultados"];
			  		}else{
			  			matcher = ["Este proveedor no tiene productos o marcas agregadas"];
			  		}
		  		}
		  		response(matcher);
		  	},
		  	select: function(event, ui){
		  		var item = ui.item;
		  		var parent = selector.parent();
		  		if(item.token)
		  		{
			  		setTimeout(function(){
				  		parent.find('.description').text(item.description);
				  		parent.find('.code').text(item.code);
				  		parent.find('.units').text(item.units);
				  		parent.find('.type').text(item.type[0].toUpperCase());
				  		parent.find('.amount').text(1);
				  		parent.find('.cost').text(100);
				  		parent.find('.discount').text(0);
				  		parent.find('.token').val(item.token);
				  		totalRow($('.TableDetail .item').eq(0));
				  		calculateTotal('in');
			  		},250);
		  		}else{
		  			setTimeout(function(){
		  				parent.find('.description').text('');
			  		},250);
		  		}
		  	}
		});
	};

	var totalRow = function (row){
		var amount = row.find('.amount').text();
		var cost = row.find('.cost').text();
		var discount = row.find('.discount').text();
		var subtotal = amount * cost;
		var total = subtotal - ( (subtotal * discount) / 100 );
		if(!isNaN(total))
		{
			row.find('.total').text(total);
		}
	};

	$(document).on('blur', '.TableDetail .amount', function(e){
		var that = $(this);
		var amount = parseInt(that.text());
		var row = that.parent();
		if(row.find('.token').val().length > 0){
			if(isNaN(amount) || (amount*10) % 10 > 0 || amount <= 0)
			{
				bootbox.alert("Solo puede registrar números enteros mayores a 0.");
				that.text(1);
			}else{
				that.text(amount);
			}
			totalRow(row);
			calculateTotal('in');
		}
	});

	$(document).on('blur', '.TableDetail .cost', function(e){
		var that = $(this);
		var cost = parseFloat(that.text());
		var row = that.parent();
		if(row.find('.token').val().length > 0){
			if(isNaN(cost) || cost <= 0)
			{
				bootbox.alert("Solo puede registrar números mayores a 0.");
				that.text(1);
			}else{
				that.text(cost);
			}
			totalRow(row);
			calculateTotal('in');
		}

	});

	$(document).on('blur', '.TableDetail .discount', function(e){
		var that = $(this);
		var discount = parseFloat(that.text());
		var row = that.parent();
		if(row.find('.token').val().length > 0){
			if(isNaN(discount) || discount < 0 || discount > 100)
			{
				bootbox.alert("Solo puede registrar descuentos entre 0 y 100.");
				that.text(0);
			}else{
				that.text(discount);
			}
			totalRow(row);
			calculateTotal('in');
		}
	});

	$(document).on('focus', '.description', function(e){
		var that = $(this);
		autocomplete(that, data.products_income);
    });

	$(document).on('click', '#saveInventoriesIncome', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		var dataAjax = {};
		var product  = [];
		var amount = [];
		var cost = [];
		var discount = [];
		$(".TableDetail .token").each(function(index,value){
			product[index] = $(this).val();
		});
		$(".TableDetail .amount").each(function(index,value){
			amount[index] = parseInt($(this).text());
		});
		$(".TableDetail .cost").each(function(index,value){
			cost[index] = parseFloat($(this).text());
		});
		$(".TableDetail .discount").each(function(index,value){
			discount[index] = parseFloat($(this).text());
		});
		dataAjax.supplierInventoriesIncome        = $('#supplierIncome').val();
		dataAjax.methodInventoriesIncome          = $('#methodIncome').val();
		dataAjax.dateInventoriesIncome            = $('#dateIncome').val();
		dataAjax.invoiceInventoriesIncome         = $('#invoiceIncome').val();
		dataAjax.productsInventoriesIncome        = product;
		dataAjax.amountsInventoriesIncome         = amount;
		dataAjax.costInventoriesIncome            = cost;
		dataAjax.discountInventoriesIncome        = discount;
		dataAjax.subtotalGravadoInventoriesIncome = parseFloat($('.subtotal_gravado').text());
		dataAjax.subtotalExcentoInventoriesIncome = parseFloat($('.subtotal_excento').text());
		dataAjax.subtotalInventoriesIncome        = parseFloat($('.subtotal').text());
		dataAjax.ivaInventoriesIncome             = parseFloat($('#iva').text());
		dataAjax.discountTotalInventoriesIncome   = parseFloat($('#discount_total').text());
		dataAjax.totalInventoriesIncome           = parseFloat($('#totalIncome').val());
		ajaxForm(url,'post', dataAjax)
			.done( function (response) {
				messageAjax(response);
			});
	});

	dataTable('#table_kitchenOrders', 'productos');

	$(document).on('click', '#saveKitchenOrder', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		var rows = $('#table_kitchenOrders').dataTable().fnGetNodes();
		var products = [];
		var amount = [];
		var units = [];
		for(var i = 0; i < rows.length; i++){
			amount.push(parseFloat($(rows[i]).find(".amount").text()));
			products.push($(rows[i]).find('.token').val());
			units.push($(rows[i]).find('.unit').val());
		}
		data.productsKitchenOrders = products;
		data.amountKitchenOrders = amount;
		data.unitsKitchenOrders = units;
		ajaxForm(url, 'post', data)
			.done(function(response){
				messageAjax(response);
			});
	});

	$(document).on('blur', '#table_kitchenOrders .amount', function(e){
		var that = $(this);
		var amount = parseFloat(that.text());
		if(isNaN(amount) || amount < 0)
		{
			bootbox.alert("Solo puede registrar números mayores a 0.");
			that.text(0);
		}else{
			that.text(amount);
		}
	});


	/* Clientes */

	$(document).off('click', '#saveCustomer');
	$(document).on('click', '#saveCustomer', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.charterCustomer = $('#charterCustomer').val();
		data.fnameCustomer = $('#fnameCustomer').val();
		data.snameCustomer = $('#snameCustomer').val();
		data.flastCustomer = $('#flastCustomer').val();
		data.slastCustomer = $('#slastCustomer').val();
		data.emailCustomer = $('#emailCustomer').val();
		data.addressCustomer = $('#addressCustomer').val();
		data.phoneCustomer = $('#phoneCustomer').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	$(document).off('click', '#updateCustomer');
	$(document).on('click', '#updateCustomer', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.charterCustomer = $('#charterCustomer').val();
		data.tokenCustomer = $('#charterCustomer').data('token');
		data.fnameCustomer = $('#fnameCustomer').val();
		data.snameCustomer = $('#snameCustomer').val();
		data.flastCustomer = $('#flastCustomer').val();
		data.slastCustomer = $('#slastCustomer').val();
		data.emailCustomer = $('#emailCustomer').val();
		data.addressCustomer = $('#addressCustomer').val();
		data.phoneCustomer = $('#phoneCustomer').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	dataTable('#table_customer','Clientes');

	/* Comisionista */

	$(document).off('click', '#saveCommissionAgent');
	$(document).on('click', '#saveCommissionAgent', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameCommissionAgent = $('#nameCommissionAgent').val();
		data.commissionCommissionAgent = $('#commissionCommissionAgent').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	$(document).off('click', '#updateCommissionAgent');
	$(document).on('click', '#updateCommissionAgent', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/editar/' + $('#nameCommissionAgent').data('token');
		data.nameCommissionAgent = $('#nameCommissionAgent').val();
		data.tokenCommissionAgent = $('#nameCommissionAgent').data('token');
		data.commissionCommissionAgent = $('#commissionCommissionAgent').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	dataTable('#table_commission','Comisionista');
	/* Mesas del restaurant */
	dataTable('#table_tableSalon', 'mesas');

	$(document).off('click', '#saveTable');
	$(document).on('click', '#saveTable', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameTableSalon  = $('#nameTableSalon').val();
		data.colorTableSalon  = $('#colorTableSalon').val();
		data.restaurantTableSalon  = $('#restaurantTableSalon').val();
		data.barraTableSalon = $('#barra').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			if(data.success == 5){
				$.unblockUI();
				window.location = '/institucion/inst/salon/'+ data.url;
			}else{
				messageAjax(data);
			}
		});
	});

	$(document).off('click', '#updateTable');
	$(document).on('click', '#updateTable', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameTableSalon = $('#nameTableSalon').val();
		data.colorTableSalon  = $('#colorTableSalon').val();
		data.tokenTableSalon = $('#nameTableSalon').data('token');
		data.barraTableSalon = $('#barra').bootstrapSwitch('state');
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});


/* Menu Restaurant */

	$(document).off('click', '#saveMenuRestaruants');
	$(document).on('click', '#saveMenuRestaruants', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameMenuRestaurant = $('#nameMenuRestaurant').val();
		data.costoMenuRestaurant = $('#costoMenuRestaurant').val();
		data.typeMenuRestaurant = $('#typeMenuRestaurant').bootstrapSwitch('state');
		data.groupMenuIdMenuRestaurant = $('#groupMenuIdMenuRestaurant').val();
		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	dataTable('#table_menuRestaurant','Menu del Restaruante');

	/* Update Menu Restaurant */
	$(document).off('click', '#updateMenuRestaruants');
	$(document).on('click', '#updateMenuRestaruants', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameMenuRestaurant  = $('#nameMenuRestaurant').val();
		data.costoMenuRestaurant  = $('#costoMenuRestaurant').val();
		data.group_menu_idMenuRestaurant  = $('#group_menu_idMenuRestaurant').val();
		data.typeMenuRestaurant = $('#typeMenuRestaurant').bootstrapSwitch('state');
		data.token  = $('#nameMenuRestaurant').data('token');

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	/* Delete Component of Menu Element */
	$(document).off('click', '#delete_component');
	$(document).on('click', '#delete_component', function(e){
		e.preventDefault();
		data.idMenuRestaurantCookkedProduct = $(this).attr('data-token');
		data.delete = true;
		url = $(this).data('url');
		url = 'institucion/inst/menu-restaurante/' + url + '/deleteComponent';
		ajaxForm(url, 'post', data)
			.done(function(data){
				messageAjax(data);
			});

		/*data.tokenBrand = $(this).attr('data-token');
		 data.tokenSupplier = $('#tokenSupplier').val();
		 data.delete = true;
		 url = $(this).data('url');
		 url = 'institucion/inst/menu-restaurante/' + url + '/save';
		 ajaxForm(url, 'post', data)
		 .done(function(data){
		 messageAjax(data);
		 });*/
	});


/* Grupo de Menu */
	dataTable('#table_groupMenu','Grupo de Menus');
	dataTable('#table_invoice','Facturas Generada');


	/* Save group of Menu  */
	$(document).off('click', '#saveGroupMenu');
	$(document).on('click', '#saveGroupMenu', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameGroupMenu  = $('#nameGroupMenu').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	/* Save group of Menu  */
	$(document).off('click', '#updateGroupMenu');
	$(document).on('click', '#updateGroupMenu', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameGroupMenu  = $('#nameGroupMenu').val();
		data.tokenGroupMenu  = $('#nameGroupMenu').data('token');

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	/* Grupo de Menu */
	//dataTable('#table_groupMenu','Grupo de Menus');


	/* Save Componentes  */
	$(document).off('click', '#saveComponents');
	$(document).on('click', '#saveComponents', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/menu-restaurante/save';
		data.processedProductComponents  = $('#cookedProductComponents').val();
		data.menuComponents  = $('#cookedProductComponents').data('token');
		data.amountComponents  = $('#amountComponents').val();
		data.typeComponents = $('#typeComponents').val();
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	// Select menu_restaurant
	$(document).off('click', '.menu_restaurant');
	$(document).on('click', '.menu_restaurant', function(e){
		e.preventDefault();
		var that = $(this);
		var token = that.data('token');
		var token_table = that.data('table');
		$.get('/institucion/inst/salon/'+token_table+'/menu/'+token)
		.done(function(data){
			$('#modalMenuRestaurant .modal-content').html(data);
			$('#modalMenuRestaurant').modal('show');
		});
	});

	$(document).off('submit', '#form_salon_order');
	$(document).on('submit', '#form_salon_order', function(e){
		e.preventDefault();
		var that = $(this);
		var url = '/institucion/inst/salon-order';
		var data;
		if($('#modify_menu').val() == '0'){
			data = that.serialize();
		}else{
			data = {};
			if($('.aditional_menu').size() > 0){
				var aditionals = new Array;
				$('.product').each(function(i,v){
					if($(this).attr('data-type') == 'Adicional'){
						aditionals.push($(this).attr('data-token'));
					}
				});
				data.aditionals = aditionals;
			}
			data.menu_token = $('#menu_token').val();
			data.table_token = $('#table_token').val();
			data.modify_menu = $('#modify_menu').val();
			data.qty = $('#qty').val();
			data.items_menu = items_menu;
		}
		$.post(url,data)
		.done(function(response){
			$('#modalMenuRestaurant').modal('hide');
			messageAjax(response);
		});
	});

	$(document).off('click','#info');
	$(document).on('click','#info',function(e){
		e.preventDefault();
		var table = $(this).data('token');
		$.get('/institucion/inst/salon/orders/'+table)
		.done(function(data){
			$('#modalOrders .modal-content').html(data);
			$('#modalOrders').modal('show');
		});
	});

	// Modal print
	$(document).off('click','#print');
	$(document).on('click','#print',function(e){
		e.preventDefault();
		var table = $(this).data('token');
		if(table){
			$.get('/institucion/inst/salon/print/'+table)
			.done(function(data){
				$('#modalOrders .modal-content').html(data);
				$('#modalOrders').modal('show');
			});
		}
	});

	// Print Order Comida
	$(document).off('click', '#print_order_foods');
	$(document).on('click', '#print_order_foods', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		$.get('/institucion/inst/salon/orders_kitchen/'+token)
		.done(function(response){
			if(response.success == false){
				messageErrorAjax(response);
			}else{
				$("body").append(response);
	            window.print();
	            $('#modalOrders').modal('hide');
			}
		});
	});

	// Print Order Bebidas
	$(document).off('click', '#print_order_drinks');
	$(document).on('click', '#print_order_drinks', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		$.get('/institucion/inst/salon/orders_drinks/'+token)
			.done(function(response){
				if(response.success == false){
					messageErrorAjax(response);
				}else{
					$("body").append(response);
					window.print();
					$('#modalOrders').modal('hide');
				}
			});
	});
	// Print Order
	$(document).on('click','#printOrder a', function(e){
		e.preventDefault();
		data.token = $(this).data('token');
		$.post('/institucion/inst/order-print', data)
		.done(function(response){
			var html = response;
			$("body").append(html);
            window.print();
            $(".print-invoice").remove();
            $('#modalOrders').modal('hide');
		});
	});

	// Print Invoice
	/*$(document).on('click','#printOrder a', function(e){
		e.preventDefault();
		data.token = $(this).data('token');
		$.post('/institucion/inst/salon-print', data)
		.done(function(response){
			var html = response;
			$("body").append(html);
            window.print();
            $(".print-invoice").remove();
            $('#modalOrders').modal('hide');
		});
	});*/

	$(document).off('click','#cooked');
	$(document).on('click','#cooked', function(e){
		var token = $(this).data('token');
		var card = $(this).parent().parent().parent();
		var header = $(this).parent().parent().find('.panel-heading');
		var footer = $(this).parent();
		var url = 'institucion/inst/cocina/';
		data.token = token;
		ajaxForm(url, 'post', data)
		.done(function(data){
			$.unblockUI();
			header.addClass('bg-success');
			footer.remove();
			setTimeout(function(e){
				card.remove();
				if($('.card-restaurant.kitchen article').size() == 0){
					var msg = '<h2 class="text-center pending">No se tienen pedidos pendientes por atender.</h2>';
					$('.card-restaurant.kitchen').html(msg);
				}
			},5000);
		});
	});

	// GLobal menu
	if($('#modalMenuRestaurant').exists()){
		var items_menu = new Array();
	}

	$(document).off('click','.delete_product');
	$(document).on('click','.delete_product', function(e){
		var token = $(this).parent().parent().find('.product').data('token');
		items_menu.push(token);
		$('#modify_menu').val('1');
		var aditionals = $('.aditional').size();
		if(aditionals > 0){
			var option = '';
			$('.aditional').each(function(i,v){
				option += '<option value="'+$(this).data('token')+'">'+$(this).data('name')+'</option>';
			});
			$('#msgEdit').removeClass('hide');
			$('#aditional_menu').html(option);
		}
		if($('.delete_product').size() > 1){
			$(this).parent().parent().remove();
		}else{
			$('#form_salon_order #msg').html('<p style="padding-top:1em;"class="text-center color-red">No puede eliminar todos los productos de un menú.</p>');
		};
	});

	// Add Aditional
	$(document).off('click','#addAditional');
	$(document).on('click','#addAditional', function(e){
		var option = $('#aditional_menu option:selected');
		var tr = '<tr class="item"><td class="text-center"><button href="#" class="btn btn-danger btn-xs delete_product"><i class="fa fa-trash-o"></i></button></td><td class="product" data-token="'+option.val()+'" data-type="Adicional">'+option.text()+'</td><td class="aditional_menu">Adicional</td></tr>';
		var validate = 'true';
		$('.product').each(function(i,v){
			if($(this).text() == option.text()){
				$('#form_salon_order #msg').html('<p style="padding-top:1em;"class="text-center color-red">Ya agregó este adicional</p>');
				validate = 'false';
				return false;
			}
		});
		if(validate == 'false'){
			return false;
		}
		$('#productsMenu').append(tr);
		var total_menu = parseInt($('.num_base:last').val(),10);
		var aditional_menu = $('.aditional_menu').size();
		var base_menu = $('.base_menu').size();
		if(aditional_menu + base_menu == total_menu){
			$('#msgEdit').addClass('hide');
		}
	});


	// Cash
	$(document).off('click','#cash');
	$(document).on('click','#cash', function(e){
		var token = $(this).data('token');
		var url = '/institucion/inst/salon/'+token;
		$.post(url,token)
		.done(function(response){
			if(response.success == false){
				messageErrorAjax(response);
			}else{
				$('#modalOrders .modal-content').html(response);
				$('#modalOrders').modal('show');
			}
		});
	});

	$('#searchCash').addClass('disabled');

	$(document).off('click', '#finshCourt');
	$(document).on('click', '#finshCourt', function(e){
		$('#searchCash').removeClass('disabled');
		$('#addCurrencie').addClass('disabled');
		$('.delete_currencie').addClass('disabled');
		$('#finshCourt').addClass('disabled');
	});
		//e.preventDefault();$(document).off('click', '#searchCash');
	$(document).on('click', '#searchCash', function(e){
		e.preventDefault();
		var l = Ladda.create(this);
		var date_ini = $('#date_ini').val();
		var date_end = $('#date_end').val();
		var msg = '';
		/*var error = false;
		if(!date_ini){
			msg += "<p class='text-danger'>Debe seleccionar la fecha inicial.</p>";
			error = true;
		}
		if(!date_end){
			msg += "<p class='text-danger'>Debe seleccionar la fecha final.</p>";
			error = true;
		}
		if(error){
			bootbox.alert(msg);
		}else{*/
			var url = '/institucion/inst/caja/search';
			data.date_ini = date_ini;
			data.date_end = date_end;
			$.ajax({
				url: url,
				type:'post',
				data: data,
				beforeSend: function(e){
					l.start();
				},
			}).done(function(response){
				l.stop();
				if(response.success == false){
					messageErrorAjax(response);
				}else {
                    $('#taxed_sales').text(response.sale);
                    $('#iva').text(response.iva);
                    if (response.type == 1){
                        $('#iva').addClass('show').removeClass('hide');
                	}else{
                        $('#iva').addClass('hide').removeClass('show');
                	}
					$('#service').text(response.service);
					$('#total_sales').text(response.total_sales);
					$('#payment_supplier').text(response.payment_supplier);
					$('#totalSales').text(response.totalSales);
					$('#totalOut').text(response.totalOut);
					$.each(response.paymentMethods, function(i,v){
						$('#payment_'+i).text(v);
					});
					$('#missing').text(response.missing);
					$('#view_sale').parent().removeClass('hide');
					// View detail cash sale
					/* reporte de MenuRestaurant */
					$(document).off('click', '#view_sale');
					$(document).on('click', '#view_sale', function(e){
						e.preventDefault();
						var obj = {};
						var url = 'institucion/inst/productos-cocidos/report';
						obj.dateInCookedProduct  = date_ini;
						obj.dateOutCookedProduct = date_end;

						ajaxForm(url,'post',obj)
						.done( function (r) {
							$.unblockUI();
							window.open('/'+url);
						});
					});

				}
				calculateTotalCurrencie();
			});
		//}
	});

	$(document).off('click', '#addCurrencie');
	$(document).on('click', '#addCurrencie', function(e){
		e.preventDefault();
		var amount = $('#amount').val();
		var currencie = $('#currencies').find('option:selected');
		var value = currencie.data('value');
		var validate = true;
		$('.currencie').each(function(i,v){
			if( $(this).data('value') == value )
			{
				bootbox.alert("<p class='text-danger'>La moneda que intenta ingresar ya ha sido ingresada.</p>");
				validate = false;
			}
		});
		if(!validate){
			return false;
		}
		if(!amount || parseInt(amount) <= 0){
			var msg = "<p class='text-danger'>Debe digitar la cantidad.</p>";
			bootbox.alert(msg, function(e){
				$('#amount').focus();
			});
		}else{
			var selector = $('#currencie_detail');
			var data     = {};
			data.token   = currencie.val();
			data.value   = currencie.data('value');
			data.name    = currencie.text();
			data.amount  = amount;
			addCurrencieCash(data, selector);
		}
	});

	$(document).off('click', '.delete_currencie');
	$(document).on('click', '.delete_currencie', function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		calculateTotalCurrencie();
	});

	$(document).off('click', '#saveCourt');
	$(document).on('click', '#saveCourt', function(e){
		e.preventDefault();
		var leftover    = parseFloat(replaceComa($('#leftover').text()));
		var password    = null;
		var result      = null;
		if(leftover > 0){
			bootbox.confirm('<p class="text-danger">Va a registrar el cierre de caja con un faltante de '+ leftover +' ¿Esta seguro de continuar el cierre?</p>', function(result){
				if(result){
					bootbox.prompt({
						title: "Por favor ingrese el passwod del otro usuario.",
				        inputType: "password",
				        callback: function(result) {
				            if (result === null) {
					  			bootbox.alert("Debe ingresar el password de otro usuario.");
						  	} else {
								closingCash(result, leftover);
						  	}
				        }
					});
				}
			});
		}else{
			bootbox.prompt({
				title: "Por favor ingrese el passwod del otro usuario.",
		        inputType: "password",
		        callback: function(result) {
		            if (result === null) {
			  			bootbox.alert("<p class='text-danger'>Debe ingresar el password de otro usuario.</p>");
				  	} else {
						closingCash(result, leftover);
				  	}
		        }
			});
		}
	});

	var closingCash = function(result, leftover){

		var url         = 'institucion/inst/caja/court';
		var total_sales = parseFloat(replaceComa($('#total_sales').text()));
		$.post('/institucion/inst/caja/user',{password:result})
		.done(function(response){
			if(response.success){
				var currencies = new Array();
				$('.currencie').each(function(i,v){
					var obj = {};
					obj.id = $(this).data('id');
					obj.amount = $(this).data('amount');
					obj.name = $(this).text();
					obj.total = parseInt($(this).data('amount')) * parseFloat($(this).data('value'));
					currencies.push(obj);
				});
				data.total_sales = total_sales;
				data.currencies  = currencies;
				data.date_ini = $('#date_ini').val();
				data.date_end = $('#date_end').val();
				if(leftover > 0){
					data.missing = leftover;
				}else{
					data.surplus = Math.abs(leftover);
				}
				ajaxForm(url,'post',data)
				.done(function(response){
					$.unblockUI();
					if(response.success == false){
						messageErrorAjax(response);
					}else{
						var html = response;
						$("body").append(html);
			            window.print();
			            $(".print-invoice").remove();
			            $('#modalOrders').modal('hide');
					}
				});
			}else{
				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>");
			}
		});
	}

	/* Save Payment Suppliers  */
	$(document).off('click', '#savePaymentSuppliers');
	$(document).on('click', '#savePaymentSuppliers', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/pago-proveedores';
		data.supplierPaymentSuppler  = $('#supplierPaymentSuppler').val();
		data.numberPaymentSuppler  = $('#numberPaymentSuppler').val();
		data.amountPaymentSuppler = $('#amountPaymentSuppler').val();
		bootbox.confirm('<p class="text-danger">Esta Registrando el pago a un Proveedor esta Seguro(a)?</p>', function(result){
			if(result){
				ajaxForm(url,'post',data)
					.done( function (data) {
						messageAjax(data);
					});
			}
		});


	});


	/* Save group of Menu  */
	$(document).off('click', '#saveCurrencies');
	$(document).on('click', '#saveCurrencies', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.nameCurrencies  = $('#nameCurrencies').val();
		data.valueCurrencies = $('#valueCurrencies').val();
		ajaxForm(url,'post',data)
		.done(function(data) {
			messageAjax(data);
		});
	});

	/* Save group of Menu  */
	$(document).off('click', '#updateCurrencies');
	$(document).on('click', '#updateCurrencies', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/update';
		data.nameCurrencies  = $('#nameCurrencies').val();
		data.idCurrencies    = $('#nameCurrencies').data('token');
		data.valueCurrencies = $('#valueCurrencies').val();
		ajaxForm(url,'post',data)
		.done( function (data) {
			messageAjax(data);
		});
	});

	$("#saveTicket").on('click',function(e) {
		e.preventDefault();

		var data = new FormData($("#datafiles")[0]);
		url = $("#datafiles").attr('action');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			processData: false,
			contentType: false
		})
			.done( function (data) {
				messageAjax(data);
			});

		return false;
	});


	//$(document).off('focusout', '#pay, #missing, #usd, #pay_t');
	$(document).on('blur', '#pay', function(e){
		e.preventDefault();
		calculateTotalInvoice();
	});

	$(document).on('blur', '#missing', function(e){
		e.preventDefault();
		calculateTotalInvoice();
	});

	$(document).on('blur', '#usd', function(e){
		e.preventDefault();
		calculateTotalInvoice();
	});

	$(document).on('blur', '#pay_t', function(e){
		e.preventDefault();
		calculateTotalInvoice();
	});

	$(document).off('click', '#submit-cash');
	$(document).on('click', '#submit-cash', function(e){
		e.preventDefault();
		var $form = $('#form-closed');
		var url   = $form.attr('action');
		var data  = $form.serialize();
		var l     = Ladda.create(this);

		var total_invoice_calc = parseFloat($('#total_invoice_calc').val());
		var total              = parseFloat($('.total_invoice').text());
		var discount           = parseInt($('#discount').val());
		var missing            = parseFloat($('#missing').val());

		if(isNaN(total_invoice_calc) || total_invoice_calc < 0){
			msgErrorAmount();
			return false;
		}
		if(pay == 0 && discount != 100){
			msgErrorAmount();
			return false;
		}
		if(isNaN(discount) || discount <= 0){
			discount = 0;
		}
		if(isNaN(missing) || missing <= 0){
			missing = 0;
		}
		if(total_invoice_calc + (discount * total / 100) + missing < total){
			msgErrorAmount('Debe verificar el monto de la factura.');
			return false;
		}
		$.ajax({
			url: url,
			type:'post',
			data: data,
			beforeSend: function(e){
				l.start();
			},
		}).done(function(response){
			l.stop();
			//console.log(response);
			if(response.errors){
	            msgErrorAmount(response.errors);
			}else{
				$("body").append(response);
	            window.print();
	            $('#modalOrders').modal('hide');
			}
		});
	});

	var msgErrorAmount = function(msg){
		var error;
		if(msg){
			error = msg;
		}else{
			error = 'El monto a pagar debe ser mayor a 0.';
		}
		var alert = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+error+'</div>';
			$('#form-closed .msg').html(alert);
		$('#form-closed .msg').html(alert);
	};


	/* Update Menu Restaurant */
	$(document).off('click', '#updatePassword');
	$(document).on('click', '#updatePassword', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/updatePassword';
		data.password  = $('#password').val();
		data.confirmPassword  = $('#confirmPassword').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});

	// Datatable closedYear
	dataTable('#table_closed_year', 'cierres fiscales');

	$(document).off('click', '#new_closed_year');
	$(document).on('click', '#new_closed_year', function(e){
		e.preventDefault();
		var $this = $(this);
		bootbox.prompt({
	        title: "Ingrese su clave",
	        inputType: "password",
	        callback: function(result) {
	            if(result) {
			 		var route = 'institucion/inst/cierre-fiscal/ver'
			 		data.password = result;
			 		ajaxForm(route, 'post', data)
			 		.done( function (response){
			 			$.unblockUI();
			 			if(response.success){
			 				bootbox.alert("<p class='text-success'>"+response.message+"</p>")
			 				var step = '<p>Paso 2: Verificación de Asientos Sin Aplicar</p><a href="#" id="seat_pending_year" class="btn btn-primary"><span class="glyphicon glyphicon-book"></span> Iniciar</a><hr>';
			 				$('.step2').html(step).removeClass('hide');
			 				$this.removeClass('btn-primary').addClass('btn-default disabled');
			 			}else{
			 				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>");
			 			}
			 		});
				} else {
					bootbox.alert('No ha ingresado su Clave');
				}
	        }
	    });
	});

	$(document).off('click', '#seat_pending_year');
	$(document).on('click', '#seat_pending_year', function(e){
		e.preventDefault();
		var $this = $(this);
 		var route = 'institucion/inst/cierre-fiscal/validateSeat'
 		ajaxForm(route, 'post', data)
 		.done( function (response){
 			$.unblockUI();
 			if(response.success){
 				var string = '';
 				var data = response.message;
 				$.each(data,function(i,v){
 					string += '<p class="text-success">'+v+'</p>';
 				});
 				bootbox.alert(string);
 				var step = '<p>Paso 3: Verificación de Saldos Auxiliares</p><a href="#" id="balance_aux" class="btn btn-primary"><span class="glyphicon glyphicon-book"></span> Iniciar</a><hr>';
 				$('.step3').html(step).removeClass('hide');
 				$this.removeClass('btn-primary').addClass('btn-default disabled');
 			}else{
 				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>");
 			}
 		});
	});

	$(document).off('click', '#balance_aux');
	$(document).on('click', '#balance_aux', function(e){
		e.preventDefault();
		var $this = $(this);
 		var route = 'institucion/inst/cierre-fiscal/validateBalance'
 		ajaxForm(route, 'post', data)
 		.done( function (response){
 			$.unblockUI();
 			if(response.success){
 				bootbox.alert('<p class="text-success">'+response.message+'</p>');
 				var step = '<p>Paso 4: Impresión de Reportes</p><a href="#" id="print_reports_closed" class="btn btn-primary"><span class="fa fa-print"></span> Iniciar</a><hr>';
 				$('.step4').html(step).removeClass('hide');
 				$this.removeClass('btn-primary').addClass('btn-default disabled');
 			}else{
 				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>");
 			}
 		});
	});

	$(document).off('click', '#print_reports_closed');
	$(document).on('click', '#print_reports_closed', function(e){
		e.preventDefault();
		var $this = $(this);
 		window.open('/institucion/inst/reportes/estado-de-resultado');
 		var year = parseInt($('#year_fiscal').val());
 		var first = paddy(parseInt($('#month_first').val()),2);
 		var end = paddy(parseInt($('#month_end').val()),2);
 		window.open('/institucion/inst/reportes/balance-comprobacion/'+year+first+'-'+year+end);
		var step = '<p>Paso 5: Confirmar Cierre Fiscal</p><a href="#" id="print_finally_closed" class="btn btn-primary"><span class="fa fa-floppy-o"></span> Finalizar</a><hr>';
		$('.step5').html(step).removeClass('hide');
		$this.removeClass('btn-primary').addClass('btn-default disabled');
	});

	$(document).off('click', '#print_finally_closed');
	$(document).on('click', '#print_finally_closed', function(e){
		e.preventDefault();
		var $this = $(this);
		var route = 'institucion/inst/cierre-fiscal/finally'
 		ajaxForm(route, 'post', data)
 		.done( function (response){
 			$.unblockUI();
 			if(response.success){
 				bootbox.alert('<p class="text-success">'+response.message+'</p>',function(){
 					window.open('/institucion/inst/reportes/estado-de-resultado');
			 		var year = parseInt($('#year_fiscal').val());
			 		var first = paddy(parseInt($('#month_first').val()),2);
			 		var end = paddy(parseInt($('#month_end').val()),2);
			 		window.open('/institucion/inst/reportes/balance-comprobacion/'+year+first+'-'+year+end);

			 		//
			 		var url = 'institucion/inst/periodos-contables/save';
	 				ajaxForm(url, 'post', data)
			 		.done( function (response){
			 			$.unblockUI();
			 			if(response.success){
			 				bootbox.alert("<p class='text-success'>"+response.message+"</p>", function(){
			 					window.location = '/institucion/inst';
			 				});
			 			}else{
			 				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>")
			 			}
			 		});text-danger
 				});
 				$this.removeClass('btn-primary').addClass('btn-default disabled');
 			}else{
 				bootbox.alert("<p class='text-danger'>"+response.errors+"</p>");
 			}
 		});
	});

	// Re print Invoice
	$(document).off('click', '#printInvoice');
	$(document).on('click', '#printInvoice', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		$.get('/institucion/inst/salon/print-invoice/'+token)
		.done(function(response){
			if(response.success == false){
				messageErrorAjax(response);
			}else{
				$("body").append(response);
	            window.print();
	            $('#modalOrders').modal('hide');
			}
		});
	});

	$(document).on("hidden.bs.modal", ".bootbox.modal", function (e) {
	    $('body').css('padding',0);
	});

	// delete Order
	$(document).off('click', '.delete_order');
	$(document).on('click', '.delete_order', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		var url = 'institucion/inst/salon/order/'+token;
		data.token = token;
		ajaxForm(url, 'delete', data)
		.done(function(response){
			messageAjax(response);
		});
	});

	$(document).off('focus', '.canceled input, .canceled textarea');
	$(document).on('focus', '.canceled input, .canceled textarea', function(e){
		$('.canceled .msg').empty();
	})

	$(document).off('click', '.canceled button');
	$(document).on('click', '.canceled button', function(e){
		var token     = $(this).data('token');
		var $canceled = $('.canceled');
		var data      = {};
		var error     = '';
		data.description = $canceled.find('#description').val();
		data.password    = $canceled.find('#password').val();
		data.token       = token;
		if(!data.description){
			error +='<p class="text-danger">Debe ingresar la descripción</p>';
		}
		if(!data.password){
			error +='<p class="text-danger">Debe ingresar el password</p>';
		}
		if(error.length > 0){
			var div  = '<div class="alert alert-danger">';
			    div += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			    div += error;
			    div += '</div>';
			$canceled.find('.msg').html(div);
			return false;
		}
		var l = Ladda.create(this);
		var url = '/institucion/inst/salon-order/canceled';
		$.ajax({
			url: url,
			type:'post',
			data: {data:JSON.stringify(data)},
			beforeSend: function(e){
				l.start();
			},
		}).done(function(response){
			l.stop();
			if(response.success == false){
				var div  = '<div class="alert alert-danger">';
			    div += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			    div += '<p class="text-danger">'+response.errors+'</p>';
			    div += '</div>';
				$canceled.find('.msg').html(div);
				return false;
			}else{
				location.reload();
			}
		});
	});

	$(document).off('click', '.canceled_order');
	$(document).on('click', '.canceled_order', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		var urlTpl = getTpl('orders', 'canceled');
		$.get(urlTpl)
		.done(function(response){
			$('#modalMenuRestaurant .modal-content').html(response);
			$('#modalMenuRestaurant').modal('show');
			$('#modalMenuRestaurant button').attr('data-token', token);
		});
	});

	// Disccount
	$(document).off('focusout', '#discount');
	$(document).on('focusout', '#discount', function(e){
		e.preventDefault();
		calculateTotalInvoice();
	});

	$(document).on('change', '#paymentMethod', function(e){
		var method  = parseInt($(this).val());
		switch(method){
			case 3: $('.pay').addClass('show').removeClass('hide');
			        $('.usd').addClass('hide').removeClass('show');
			        //$('.dues').addClass('hide').removeClass('show');
			        $('.pay_t').addClass('hide').removeClass('show');
			        $('.change').addClass('show').removeClass('hide');
			        $('.change_usd').addClass('hide').removeClass('show');
			        break;
			case 4: $('.pay').addClass('hide').removeClass('show');
			        $('.usd').addClass('show').removeClass('hide');
			        //$('.dues').addClass('hide').removeClass('show');
			        $('.pay_t').addClass('hide').removeClass('show');
			        $('.change').addClass('hide').removeClass('show');
			        $('.change_usd').addClass('show').removeClass('hide');
			        break;
			case 5: $('.pay').addClass('hide').removeClass('show');
			        $('.usd').addClass('hide').removeClass('show');
			        //$('.dues').addClass('show').removeClass('hide');
			        $('.pay_t').addClass('show').removeClass('hide');
			        $('.change').addClass('show').removeClass('hide');
			        $('.change_usd').addClass('hide').removeClass('show');
			        break;
			case 7: $('.pay').addClass('show').removeClass('hide');
			        $('.usd').addClass('show').removeClass('hide');
			        //$('.dues').addClass('hide').removeClass('show');
			        $('.pay_t').addClass('hide').removeClass('show');
			        $('.change').addClass('show').removeClass('hide');
			        $('.change_usd').addClass('show').removeClass('hide');
			        break;
			case 8: $('.pay').addClass('show').removeClass('hide');
			        $('.usd').addClass('hide').removeClass('show');
			        //$('.dues').addClass('show').removeClass('hide');
			        $('.pay_t').addClass('show').removeClass('hide');
			        $('.change').addClass('show').removeClass('hide');
			        $('.change_usd').addClass('hide').removeClass('show');
			        break;
			case 9: $('.pay').addClass('hide').removeClass('show');
			        $('.usd').addClass('show').removeClass('hide');
			        //$('.dues').addClass('show').removeClass('hide');
			        $('.pay_t').addClass('show').removeClass('hide');
			        $('.change').addClass('hide').removeClass('show');
			        $('.change_usd').addClass('show').removeClass('hide');
			        break;
		}
		calculateTotalInvoice();
	});

	$(document).off('click','#re-print');
	$(document).on('click','#re-print', function(e){
		e.preventDefault();
		var token = $(this).data('token');
		var url = '/institucion/inst/facturas-reimpresion/'+token;
		$.get(url)
		.done(function(response){
			$("body").append(response);
            window.print();
            $('#modalOrders').modal('hide');
		});
	});


	//reimprimir asientos
	$(document).off('click', '#reimprimirSeating');
	$(document).on('click', '#reimprimirSeating', function(e){
		e.preventDefault();
		var token = $('#tokenSeatingReprintInitial').val();
		var token1 = $('#tokenSeatingReprintFinish').val();
		var url = '/institucion/inst/reportes/asientos/excel/'+token+'/'+token1;
		$.get(url)
			.done(function(response){
				messageAjax(response);

			});
	});


	/* Save Employess */
	$(document).off('click', '#saveEmployess');
	$(document).on('click', '#saveEmployess', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/' + url + '/save';
		data.charterEmployess  = $('#charterEmployess').val();
		data.fnameEmployess  = $('#fnameEmployess').val();
		data.snameEmployess  = $('#snameEmployess').val();
		data.flastEmployess  = $('#flastEmployess').val();
		data.slastEmployess  = $('#slastEmployess').val();
		data.phoneEmployess  = $('#phoneEmployess').val();
		data.emailEmployess  = $('#emailEmployess').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});


	/* Update Employess */
	$(document).off('click', '#updateEmployess');
	$(document).on('click', '#updateEmployess', function(e){
		e.preventDefault();
		url = $(this).data('url');
		var token = $(this).data('token');
		url = 'institucion/inst/' + url + '/update/'+ token;
		data.tokenEmployess  = token;
		data.charterEmployess  = $('#charterEmployess').val();
		data.fnameEmployess  = $('#fnameEmployess').val();
		data.snameEmployess  = $('#snameEmployess').val();
		data.flastEmployess  = $('#flastEmployess').val();
		data.slastEmployess  = $('#slastEmployess').val();
		data.phoneEmployess  = $('#phoneEmployess').val();
		data.emailEmployess  = $('#emailEmployess').val();

		ajaxForm(url,'put',data)
			.done( function (data) {
				messageAjax(data);
			});
	});



	/* Save Horas Employess */
	$(document).off('click', '#save-registro-de-horas-Employess');
	$(document).on('click', '#save-registro-de-horas-Employess', function(e){
		e.preventDefault();
		url = $(this).data('url');
		url = 'institucion/inst/empleado/' + url + '/save';
		data.tokenEmployess  = $('#tokenEmployess').val();
		data.dateEmployess  = $('#dateEmployess').val();
		data.timesEmployess  = $('#timesEmployess').val();

		ajaxForm(url,'post',data)
			.done( function (data) {
				messageAjax(data);
			});
	});
/*fin*/

});

$(function mueveReloj(){

	/*Esta funcion le agrega un 0
	 a una variable i que sea menor a 10*/
	momentoActual = new Date()
	hora = momentoActual.getHours()
	minuto = momentoActual.getMinutes()
	segundo = momentoActual.getSeconds()
	date = momentoActual.getDate()
	month = momentoActual.getMonth()
	year = momentoActual.getFullYear()
	str_segundo = new String (segundo)
	if (str_segundo.length == 1)
		segundo = "0" + segundo

	str_minuto = new String (minuto)
	if (str_minuto.length == 1)
		minuto = "0" + minuto

	str_hora = new String (hora)
	if (str_hora.length == 1)
		hora = "0" + hora

	dateImprimible =  year + "-" + month + "-" + date
	horaImprimible =  hora + ":" + minuto + ":" + segundo

	$("#dateEmployess").attr('value',dateImprimible);
	$("#timesEmployess").attr('value',horaImprimible);
	 setTimeout(function(){mueveReloj()},30);

});



(function() {

    var beforePrint = function() {
        //console.log('Functionality to run before printing.');
    };

    var afterPrint = function() {
        window.location.reload();
        $('#modalMenuRestaurant').modal('hide');
    };

    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }

    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

}());