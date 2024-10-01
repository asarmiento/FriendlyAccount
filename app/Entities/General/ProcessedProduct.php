<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 03:03 PM
 */

namespace AccountHon\Entities\General;



use AccountHon\Entities\Entity;
use AccountHon\Entities\Restaurant\CookedProductInvoice;
use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Entities\Restaurant\Recipe;

class ProcessedProduct extends Entity
{

    protected  $fillable = ['code','name','token','price','school_id','type','numberOfDishes'];

    public function getRules()
    {
        return ['name'          =>'required',
                'code'          =>'required',
                'token'         =>'required',
                'price'         =>'required',
                'type'         =>'required',
                'numberOfDishes'=>'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $datos['name'] .= ',name,'.$datos->id;
        return $datos;// TODO: Implement getUnique() method.
    }

    public function cookedProductInvoice()
    {
        return $this->belongsToMany(Invoice::getClass(),'cooked_product_invoices')->withPivot('amount','price');
    }


    public function cookedProducts()
    {
        return $this->hasMany(CookedProductInvoice::getClass());
    }

    public function recipts()
    {
        return $this->hasMany(Recipe::getClass(),'cooked_product_id','id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::getClass(),'cooked_product_invoices');
    }
}