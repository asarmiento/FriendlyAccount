<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 01/01/16
 * Time: 12:54 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\RawMaterial;

class Recipe extends  Entity
{

    protected  $fillable = ['amount','token','units','unitsIn','rawProduct_id','cooked_product_id'];

    public function getRules()
    {
        return ['units'=>'required','token'=>'required','amount'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function rawProducts()
    {
        return $this->belongsTo(RawMaterial::getClass(),'rawProduct_id','id');
    }
    public function rawProduct()
    {
        return $this->belongsTo(RawMaterial::getClass(),'rawProduct_id','id');
    }
}