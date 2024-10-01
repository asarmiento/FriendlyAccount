$('#form_split').on('submit', function(e){
	e.preventDefault();
	var $this = $(this);
	var number_clients = $this.find('#clients').val();
	var clients = [];
	for(var i = 0; i < number_clients; i++){
		clients.push('Cliente Genérico '+ (i+1));
	}
	var urlTpl = getTpl('orders', 'split_orders');
	$.get(urlTpl)
	.done(function(templattemplate){
		var ractive = new Ractive({
			el: '#split_orders_detail',
			template: templattemplate,
			data: {
				clients: clients,
				orders: orders,
				exchange: exchange,
				subtotal: function(){
					var subtotal = 0;
					$.each(orders, function(i,v){
						var item = $(this)[0];
						if(item.menu_restaurant.money == 'dolares'){
							subtotal += parseFloat(item.menu_restaurant.costo) * parseInt(item.total) * exchange;
						}else{
							subtotal += parseFloat(item.menu_restaurant.costo) * parseInt(item.total);
						}
					});
					return subtotal;
				}
			}
		});

		ractive.observe('orders.*.total', function ( newValue, oldValue, keypath ) {
			newValue = parseInt(newValue);
			oldValue = parseInt(oldValue);

			var index = keypath.split('.')[0]+'.'+keypath.split('.')[1];
			var model = ractive.get(index);
			if(!model){
				return false;
			}
			var menu_restaurant = model.menu_restaurant_id;
			var ori_tot = 0;
			var mod_tot = 0;
			var error = false;

			// Each orders_ori
			// get total_ori
			for(var i = 0; i < orders_ori.length; i++)
			{
				if(orders_ori[i].menu_restaurant_id == menu_restaurant){
					ori_tot = parseInt(orders_ori[i].total);
					break;
				}
			}

			// Orders model
			$.each(ractive.get('orders'), function(i,v){
				var order = $(this)[0];
				if(order.menu_restaurant_id == menu_restaurant){
					mod_tot += parseInt(order.total);
				}
			});

			index = keypath.split('.')[1];

			// Validate if number
			if(isNaN(newValue) || newValue < 1 || newValue === ''){
				bootbox.alert("<p class='text-danger'>Debe ingresar un número mayor o igual a 1.</p>");
	    		ractive.set('orders.'+[index]+'.total', oldValue);
		    	return false;
			}

			// Validate mayor orig
			if(newValue > ori_tot){
	    		bootbox.alert("<p class='text-danger'>No puede ingresar una cantidad mayor a la orden solicitada: "+ori_tot+"</p>");
	    		ractive.set('orders.'+[index]+'.total', oldValue);
	    		orders_ori[i].total = ori_tot;
	    		return false;
	    	}

	    	// Validate total edit
	    	if(mod_tot > ori_tot){
	    		bootbox.alert("<p class='text-danger'>No puede ingresar una cantidad mayor a la orden solicitada: "+ori_tot+"</p>");
	    		ractive.set('orders.'+[index]+'.total', oldValue);
	    		orders_ori[i].total = ori_tot;
	    		return false;
	    	}else{
				// Validate if qty is hihger
				if(newValue < oldValue){
					$.each(orders_ori, function(i,v){
						var item = $(this)[0];
						if(item.menu_restaurant_id == menu_restaurant){
							var order_new = $.extend({}, orders_ori[i]);
							order_new.split = true;
							order_new.total = oldValue - newValue;
							orders.push(order_new);
						}
					});
				}
	    	}
		},{
			init: false,
			defer: true
		});

		ractive.on("calculate", function(r){
			var clients_order    = $('.clients');
			var clients_finnaly  = []; // array ? int
			var clients_selected = []; // array ? string detail order
			var clients_pay      = []; // array ? obj

			$.each(clients_order, function(index,value){
				var $item = $(this).find('select option:selected');
				clients_selected.push($item.text());
				// Solo clients
				if(clients_finnaly.indexOf($item.val()) == -1){
					clients_finnaly.push($item.val());
					var data = {};
					data.name          = $item.text();
					data.discount      = 0;
					data.total         = 0;
					data.total_pay     = 0;
					data.change        = 0;
					data.pay           = 0;
					data.pay_t         = 0;
					data.usd           = 0;
					data.paymentMethod = payments_method[0].id;
					//data.dues          = 1;
					data.change_usd    = 0;
					data.missing       = 0;
					data.payment_total = 0;
					data.new_total     = 0;
					clients_pay.push(data);
				}
				// clients_pay -> model
				$.each(clients_pay, function(i,v){
					var item = $(this)[0];
					if($item.text() == item.name){
						var sub;
						if(orders[index].menu_restaurant.money == 'dolares'){
							sub = parseFloat(orders[index].menu_restaurant.costo) * parseInt(orders[index].total) * exchange * (1 + iva);
						}else{
							sub = parseFloat(orders[index].menu_restaurant.costo) * parseInt(orders[index].total) * (1 + iva);
						}
						var service = sub / (1 + iva);
						if(table.barra == '0'){
							sub += service * isv;
						}
						item.total = Number(item.total) + sub;
						item.total = item.total.toFixed(2);
						item.total_pay = Number(item.total_pay) + sub;
						item.total_pay = item.total_pay.toFixed(2);
						item.change = Number(item.change) + (sub*-1);
						item.change = item.change.toFixed(2);
						item.change_usd = Number(item.change_usd) + (sub/exchange * -1) + 0.01;
						item.change_usd = item.change_usd.toFixed(2);
					}
				});
			});

			if(clients_finnaly.length == 1){
				bootbox.alert("<p class='text-danger'>Debe escoger al menos 02 clientes para dividir la orden.</p>")
			}else{
				var urlTpl = getTpl('orders', 'total_users');
				$.get(urlTpl)
				.done(function(template){
					var ractive_total = new Ractive({
						el: '#total_user',
						template: template,
						data: {
							payments_method: payments_method,
							clients_pay: clients_pay,
							clients_selected: clients_selected,
							password: null
						}
					});

					var enlace  = $('#total_user');
				    $('html, body').animate({
			        	scrollTop: $(enlace).offset().top - 100
			    	}, 1000);

					// Payment method -- OK
					ractive_total.observe('clients_pay.*.paymentMethod', function(newValue,oldValue,keyPath){
						newValue = Number(newValue);
						oldValue = Number(oldValue);

						var index = keyPath.split('.')[1];

						$form_user = $('#total_user .row:eq('+index+')');

						switch(newValue){
							case 3: $form_user.find('.pay').addClass('show').removeClass('hide');
							        $form_user.find('.usd').addClass('hide').removeClass('show');
							        //$form_user.find('.dues').addClass('hide').removeClass('show');
							        $form_user.find('.pay_t').addClass('hide').removeClass('show');
							        $form_user.find('.change').addClass('show').removeClass('hide');
							        $form_user.find('.change_usd').addClass('hide').removeClass('show');
							        break;
							case 4: $form_user.find('.pay').addClass('hide').removeClass('show');
							        $form_user.find('.usd').addClass('show').removeClass('hide');
							        //$form_user.find('.dues').addClass('hide').removeClass('show');
							        $form_user.find('.pay_t').addClass('hide').removeClass('show');
							        $form_user.find('.change').addClass('hide').removeClass('show');
							        $form_user.find('.change_usd').addClass('show').removeClass('hide');
							        break;
							case 5: $form_user.find('.pay').addClass('hide').removeClass('show');
							        $form_user.find('.usd').addClass('hide').removeClass('show');
							        //$form_user.find('.dues').addClass('show').removeClass('hide');
							        $form_user.find('.pay_t').addClass('show').removeClass('hide');
							        $form_user.find('.change').addClass('show').removeClass('hide');
							        $form_user.find('.change_usd').addClass('hide').removeClass('show');
							        break;
							case 7: $form_user.find('.pay').addClass('show').removeClass('hide');
							        $form_user.find('.usd').addClass('show').removeClass('hide');
							        //$form_user.find('.dues').addClass('hide').removeClass('show');
							        $form_user.find('.pay_t').addClass('hide').removeClass('show');
							        $form_user.find('.change').addClass('show').removeClass('hide');
							        $form_user.find('.change_usd').addClass('show').removeClass('hide');
							        break;
							case 8: $form_user.find('.pay').addClass('show').removeClass('hide');
							        $form_user.find('.usd').addClass('hide').removeClass('show');
							        //$form_user.find('.dues').addClass('show').removeClass('hide');
							        $form_user.find('.pay_t').addClass('show').removeClass('hide');
							        $form_user.find('.change').addClass('show').removeClass('hide');
							        $form_user.find('.change_usd').addClass('hide').removeClass('show');
							        break;
							case 9: $form_user.find('.pay').addClass('hide').removeClass('show');
							        $form_user.find('.usd').addClass('show').removeClass('hide');
							        //$form_user.find('.dues').addClass('show').removeClass('hide');
							        $form_user.find('.pay_t').addClass('show').removeClass('hide');
							        $form_user.find('.change').addClass('show').removeClass('hide');
							        $form_user.find('.change_usd').addClass('show').removeClass('hide');
							        break;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Discount
					ractive_total.observe('clients_pay.*.discount', function(newValue,oldValue,keyPath){
						newValue = parseFloat(newValue);
						oldValue = parseFloat(oldValue);

						var index = keyPath.split('.')[1];

						if(isNaN(newValue) || newValue < 0 || newValue === '' || newValue>100){
							bootbox.alert("<p class='text-danger'>Debe ingresar un número entre 0 a 100.</p>");
				    		ractive_total.set('clients_pay.'+[index]+'.discount', oldValue);
					    	return false;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Colones
					ractive_total.observe('clients_pay.*.pay', function(newValue,oldValue,keyPath){
						newValue = parseFloat(newValue);
						oldValue = parseFloat(oldValue);

						var index = keyPath.split('.')[1];
						if(isNaN(newValue) || newValue < 0 || newValue === ''){
							bootbox.alert("<p class='text-danger'>Debe ingresar un número mayor o igual a 0.</p>");
				    		ractive_total.set('clients_pay.'+[index]+'.pay', oldValue);
					    	return false;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Tarjeta
					ractive_total.observe('clients_pay.*.pay_t', function(newValue,oldValue,keyPath){
						newValue = parseFloat(newValue);
						oldValue = parseFloat(oldValue);

						var index = keyPath.split('.')[1];
						if(isNaN(newValue) || newValue < 0 || newValue === ''){
							bootbox.alert("<p class='text-danger'>Debe ingresar un número mayor o igual a 0.</p>");
				    		ractive_total.set('clients_pay.'+[index]+'.pay_t', oldValue);
					    	return false;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Dolares
					ractive_total.observe('clients_pay.*.usd', function(newValue,oldValue,keyPath){
						newValue = parseFloat(newValue);
						oldValue = parseFloat(oldValue);

						var index = keyPath.split('.')[1];

						if(isNaN(newValue) || newValue < 0 || newValue === ''){
							bootbox.alert("<p class='text-danger'>Debe ingresar un número mayor o igual a 0.</p>");
				    		ractive_total.set('clients_pay.'+[index]+'.usd', oldValue);
					    	return false;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Missing
					ractive_total.observe('clients_pay.*.missing', function(newValue,oldValue,keyPath){
						newValue = parseFloat(newValue);
						oldValue = parseFloat(oldValue);

						var index = keyPath.split('.')[1];

						if(isNaN(newValue) || newValue < 0 || newValue === ''){
							bootbox.alert("<p class='text-danger'>Debe ingresar un número mayor o igual a 0.</p>");
				    		ractive_total.set('clients_pay.'+[index]+'.missing', oldValue);
					    	return false;
						}
						calculateTotalInvoice(index);
					},{
						init: false,
						defer: true
					});

					// Save Order
					ractive_total.on("save", function(r){
						var orders            = ractive.get('orders');
						var clients_selected  = ractive_total.get('clients_selected');
						var clients_pay       = ractive_total.get('clients_pay');
						var password          = ractive_total.get('password');
						var discount_validate = false;
						var error = '';
						for(var item in clients_pay){
							var client = clients_pay[item];
							var discount = client.discount;
							var new_total = Number(client.total) * (100 - discount) / 100;
							if(client.payment_total < new_total){
								error += '<p class="text-danger">Por favor verifique la cuenta del: '+client.name+'</p>';
							}
							if(client.discount > 0){
								discount_validate = true;
							}
						}
						if(error.length > 0){
							bootbox.alert(error);
						}else{
							if(discount_validate){
								if(password == null || password == ''){
									bootbox.alert('<p class="text-danger">Debe ingresar la contraseña para aplicar el descuento.</p>');
									return false;
								}
							}
							var data = {
								orders: orders,
								clients_selected: clients_selected,
								clients_pay: clients_pay,
								password: password,
								table: table.token
							}
							ajaxForm('institucion/inst/cashSplit', 'post', data)
							.done(function(response){
								if(response.success == false){
									messageErrorAjax(response);
								}else{
									$.unblockUI();
									$("body").append(response);
						            window.print();
								}
							});
						}
					});

					// Method calculate Total Invoice
					var calculateTotalInvoice = function(index){
						var discount   = Number(ractive_total.get('clients_pay.'+[index]+'.discount'));
						var total      = Number(ractive_total.get('clients_pay.'+[index]+'.total'));
						var total_pay  = Number(ractive_total.get('clients_pay.'+[index]+'.total_pay'));
						var change     = Number(ractive_total.get('clients_pay.'+[index]+'.change'));
						var change_usd = Number(ractive_total.get('clients_pay.'+[index]+'.change_usd'));
						var pay        = Number(ractive_total.get('clients_pay.'+[index]+'.pay'));
						var pay_t      = Number(ractive_total.get('clients_pay.'+[index]+'.pay_t'));
						var usd        = Number(ractive_total.get('clients_pay.'+[index]+'.usd'));
						var dues       = Number(ractive_total.get('clients_pay.'+[index]+'.dues'));
						var missing    = Number(ractive_total.get('clients_pay.'+[index]+'.missing'));

						var payment_method = Number(ractive_total.get('clients_pay.'+[index]+'.paymentMethod'));

						// New colones
						var new_tot_pay = total * (100 - discount) / 100;

						// New usd
						var new_tot_usd = new_tot_pay / exchange;

						// Missing
						var new_change = change;
						var new_change_usd = change_usd;

						// Colones
						if(payment_method == 3){
							new_change = new_tot_pay - missing - pay;
							new_change = new_change * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_pay);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', pay + missing);
						// Dolares
						}else if(payment_method == 4){
							new_change_usd = new_tot_usd - (missing/exchange) - usd;
							new_change_usd = new_change_usd * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_usd);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', usd * exchange);
						// Tarjeta
						}else if(payment_method == 5){
							new_change = new_tot_pay - missing + pay_t;
							new_change = new_change * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_pay);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', pay_t);
						// Colones y Dolares
						}else if(payment_method == 7){
							new_change = new_tot_pay - missing - pay - (usd * exchange);
							new_change = new_change * -1;
							new_change_usd = new_tot_usd - usd - (pay / exchange);
							new_change_usd = new_change_usd * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_pay);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', pay + usd * exchange);
						// Tarjeta y Colones
						}else if(payment_method == 8){
							new_change = new_tot_pay - missing - pay - pay_t;
							new_change = new_change * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_pay);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', pay + pay_t);
						// Tarjeta y Dolares
						}else if(payment_method == 9){
							new_change = new_tot_pay - missing - pay_t - (usd * exchange);
							new_change = new_change * -1;
							new_change_usd = new_tot_usd - usd - (pay_t / exchange);
							new_change_usd = new_change_usd * -1;
							ractive_total.set('clients_pay.'+[index]+'.new_total', new_tot_pay);
							ractive_total.set('clients_pay.'+[index]+'.payment_total', pay_t + usd * exchange);
						}

						new_tot_pay = new_tot_pay.toFixed(2);
						new_change = new_change.toFixed(2);

						new_tot_usd = new_tot_usd.toFixed(2);
						new_change_usd = new_change_usd.toFixed(2);

						ractive_total.set('clients_pay.'+[index]+'.total_pay', new_tot_pay);
						ractive_total.set('clients_pay.'+[index]+'.total_pay_usd', new_tot_usd);
						ractive_total.set('clients_pay.'+[index]+'.change', new_change);
						ractive_total.set('clients_pay.'+[index]+'.change_usd', new_change_usd);
					}
				});

			}
		});

		ractive.on("delete", function(r){
			var key = r.keypath;
			var order = ractive.get(key);
			key = key.split('.')[1];
			ractive.splice('orders', key, 1);
			$.each(orders, function(i,v){
				var item = $(this)[0];
				if(item.menu_restaurant_id == order.menu_restaurant_id){
					item.total = Number(item.total) + Number(order.total);
					ractive.update('orders');
					return false;
				}
			});
		});
	});
});