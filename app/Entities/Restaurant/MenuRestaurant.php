<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/01/16
 * Time: 08:52 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\ProcessedProduct;

class MenuRestaurant extends Entity
{
    protected $fillable =['school_id','user_id','name','costo','token','type','group_menu_id'];

    public function getRules()
    {
        return ['school_id'=>'required',
            'user_id'=>'required',
            'name'=>'required',
            'costo'=>'required',
            'group_menu_id'=>'required',
            'token'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {

      // $rules['name'] .= ',name,'.$this->id;
        return $rules;// TODO: Implement getUnique() method.

    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : ${DATE}
     * @Update     : 2017-05-22
     ********************************************************
     * @Description: Cambio de nombre de relacion
     *
     *
     *
     * @Pasos      :
     *
     *
     *
     * @return $this
     ********************************************************/
    public function processedProduct()
    {
        return $this->belongsToMany(ProcessedProduct::getClass(),'menurestaurant_cookedproduct','menu_restaurant_id','cooked_product_id')->withPivot('amount','type');
    }

    public function cookedProduct()
    {
        return $this->belongsToMany(ProcessedProduct::getClass(),'menurestaurant_cookedproduct','menu_restaurant_id','cooked_product_id')->withPivot('amount','type');
    }
    public function cookedProductBaseNotIn($column, $array)
    {
        return $this->belongsToMany(ProcessedProduct::getClass(),'menurestaurant_cookedproduct','menu_restaurant_id','cooked_product_id')->withPivot('amount')->where('menurestaurant_cookedproduct.type', 'Base')->whereNotIn($column, $array)->get();
    }

    public function groupMenus()
    {
        return $this->belongsTo(GroupMenu::getClass(),'group_menu_id','id');
    }

    public function menurestaurantCookedproduct()
    {
        return $this->hasMany(MenuRestaurantCookedProduct::getClass(),'menu_restaurant_id','cooked_product_id');
    }
}