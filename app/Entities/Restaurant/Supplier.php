<?php

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class Supplier extends  Entity
{
	protected  $fillable = ['amount','token','units','rawProduct_id','cooked_product_id'];

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

}