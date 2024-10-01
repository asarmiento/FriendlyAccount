<?php

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use AccountHon\Entities\User;

class OrderSalon extends Entity
{
    protected $fillable = ['user_id', 'menu_restaurant_id', 'table_salon_id', 
                           'qty','date', 'modify', 'status','token', 'invoice_id','split_user_id',
                           'canceled', 'description'];
    public function getRules()
    {
        return [ 'user_id'=>'required',
                 'menu_restaurant_id'=>'required',
                 'table_salon_id'=>'required',
                 'qty' => 'required',
                 'date' => 'required',
                 'token' => 'required'
               ];
    }

    public function getUnique($rules, $datos)
    {
        $rules['name'] .= ',name,' . $datos->id;
        return $rules;
    }

    public function menuRestaurant(){
        return $this->belongsTo(MenuRestaurant::getClass(), 'menu_restaurant_id', 'id');
    }

    public function tableSalon(){
        return $this->belongsTo(TableSalon::getClass(), 'table_salon_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::getClass(), 'user_id', 'id');
    }

    public function modifyMenu(){
        return $this->hasMany(ModifyOrderSalon::getClass(), 'order_salon_id', 'id');
    }
}