<?php
/**
 * Created by PhpStorm.
 * User: acondarcoi
 * Date: 2/2/16
 * Time: 7:08 PM
 */

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use AccountHon\Entities\General\ProcessedProduct;

class MenuRestaurantCookedProduct extends  Entity
{
    protected $table = 'menurestaurant_cookedproduct';

    protected  $fillable = ['amount','cooked_product_id','menu_restaurant_id','user_id','type'];

    public function getRules()
    {
        return ['amount'=>'required',
                'cooked_product_id'=>'required',
                'type'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function cookedProducts()
    {
        return $this->belongsTo(ProcessedProduct::getClass(),'cooked_product_id','id');
    }


}