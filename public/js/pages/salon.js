var calculateTotalInvoice = function($this){
	var $change     = $('#change');
	var $change_usd = $('#change_usd');
	var amount = parseFloat($('#pay').val());
	var amount_usd = parseFloat($('#usd').val());
	var amount_t = parseFloat($('#pay_t').val());
	var total = parseFloat($('.total_invoice').text());
	var $total_invoice_calc = $('#total_invoice_calc');


	var $discount = $('#discount');
	var discount = 0;

	// Descuentos
	if($discount.val() <= 0){
		$discount.val(0);
		$('.discount').text(0);
		$('.total_invoice_discount').text(' '+total);
		if(isNaN(amount)){
			return false;
		}
	}else if($discount.val() >= 100){
		$discount.val(100);
		discount = 100;
		total = 0;
		$('.discount').text(100);
		$('.total_invoice_discount').text(' '+total);
		$change.val(0);
		$change_usd.val(0);
		if(isNaN(amount) || amount == 0)
		{
			return false;
		}
	}else{
		discount = $discount.val();
		$('.discount').text(discount);
		total = total * (100 - discount) /  100;
		$('.total_invoice_discount').text(' '+total);
		if(isNaN(amount))
		{
			return false;
		}
	}

	var exchange = $('#exchange').val();
	var total_usd = (total/exchange) + 0.01;
	var missing = parseFloat($('#missing').val());
	var payment_method = $('#paymentMethod').val();
	
	// Colones
	if(payment_method == 3){
		if(amount <= 0 || isNaN(amount)){
			amount = 0;
		}
		if(amount < total){
			if(missing > 0){
				amount += missing;
			}
		}
		change = amount - total;
		change_usd = total / exchange;
		$total_invoice_calc.val(amount);
	// Dolares
	} if(payment_method == 4){
		
		if(amount_usd <= 0 || isNaN(amount_usd)){
			amount_usd = 0;
		}
		if(amount_usd > 0){
			if(missing > 0){
				amount_usd += missing;
			}
		}
		change = total;
		change_usd = amount_usd - total_usd;
		$total_invoice_calc.val(amount_usd * exchange);
	// Tarjeta
	} if(payment_method == 5){
		if(amount_t <= 0 || isNaN(amount_t)){
			amount_t = 0;
		}
		if(amount_t < total){
			if(missing > 0){
				amount_t += missing;
			}
		}
		change = amount_t - total;
		change_usd = total / exchange;
		$total_invoice_calc.val(amount_t);
	// Colones y Dólares
	} if(payment_method == 7){
		if(amount <= 0 || isNaN(amount)){
			amount = 0;
		}
		if(amount > 0){
			if(amount <= total){
				if(missing > 0){
					amount += missing;
				}
			}
			change = amount - total;
			change_usd = (amount / exchange) - total_usd;
		}
		if(amount_usd <= 0 || isNaN(amount_usd)){
			amount_usd = 0;
		}
		if(amount_usd > 0){
			change_usd = (amount_usd + (amount / exchange)) - total_usd;
			change = ((amount_usd * exchange) + amount) - total;
		}else{
			change_usd = amount / exchange - total_usd;
			change = amount - total;
		}
		$total_invoice_calc.val(amount + (amount_usd * exchange));
	// Tarjeta y Colones
	} if(payment_method == 8){
		if(amount <= 0 || isNaN(amount)){
			amount = 0;
		}
		if(amount > 0){
			if(amount <= total){
				if(missing > 0){
					amount += missing;
				}
			}
			change = amount - total;
		}
		if(amount_t <= 0 || isNaN(amount_t)){
			amount_t = 0;
		}
		if(amount_t > 0){
			change = amount_t + amount - total;
		}else{
			change = amount - total;
		}
		$total_invoice_calc.val(amount + amount_t);
	// Tarjeta y Dólares
	} if(payment_method == 9){
		if(amount_t <= 0 || isNaN(amount_t)){
			amount_t = 0;
		}
		if(amount_t > 0){
			if(amount_t <= total){
				if(missing > 0){
					amount_t += missing;
				}
			}
			change = amount_t - total;
			change_usd = (amount_t / exchange) - total_usd;
		}
		if(amount_usd <= 0 || isNaN(amount_usd)){
			amount_usd = 0;
		}
		if(amount_usd > 0){
			change_usd = (amount_usd + (amount_t / exchange)) - total_usd;
			change = ((amount_usd * exchange) + amount_t) - total;
		}else{
			change_usd = amount_t / exchange - total_usd;
			change = amount_t - total;
		}
		$total_invoice_calc.val(amount_t + (amount_usd * exchange));
	}
	if(amount > 0){
		$('#form-closed .msg').empty();
		$change.val(change.toFixed(2));
		$change_usd.val(change_usd.toFixed(2));
	}else if(amount < 0 && discount == 100){
		$('#form-closed .msg').empty();
		$change.val(change.toFixed(2));
		$change_usd.val(change_usd.toFixed(2));
	}else{
		$change.val((total*-1).toFixed(2));
		$change_usd.val((total_usd*-1).toFixed(2));
		msgErrorAmount();
	}
};