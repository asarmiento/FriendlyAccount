<?php

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use AccountHon\Entities\General\ProcessedProduct;
use AccountHon\Entities\User;

class ModifyOrderSalon extends Entity
{
    protected $fillable = ['order_salon_id', 'cooked_product_id', 'user_id','type'];
    public function getRules()
    {
        return [ 'user_id'=>'required',
                 'order_salon_id'=>'required',
                 'cooked_product_id'=>'required',
                 'type'=> 'required'
               ];
    }

    public function getUnique($rules, $datos)
    {
        //$rules['name'] .= ',name,' . $datos->id;
        //return $rules;
    }

    public function cookedProduct(){
        return $this->belongsTo(ProcessedProduct::getClass(), 'cooked_product_id', 'id');
    }

    public function orderSalon(){
        return $this->belongsTo(OrderSalon::getClass(), 'order_salon_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::getClass(), 'user_id', 'id');
    }
}