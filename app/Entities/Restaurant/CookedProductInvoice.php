<?php

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use Illuminate\Database\Eloquent\Model;

class CookedProductInvoice extends Entity
{

    protected  $fillable = ['cooked_product_id','menu_restaurant_id','invoice_id','amount'];

    public function getRules()
    {
        return [
        	'cooked_product_id'  => 'required',
            'menu_restaurant_id' => 'required',
            'invoice_id'         => 'required',
            'amount'             => 'required',
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function menuRestaurant(){
        return $this->belongsTo(MenuRestaurant::getClass(), 'menu_restaurant_id', 'id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::getClass());
    }
}