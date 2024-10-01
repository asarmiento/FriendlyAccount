<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 23/11/16
 * Time: 09:29
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;
use AccountHon\Entities\Restaurant\Supplier;

class PaymentSupplier extends Entity
{
    protected $fillable =['supplier_id','amount','date','user_id','number','token','status'];

    public function getRules()
    {
        return ['supplier_id'=>'required','amount'=>'required','number'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
       return $rules; // TODO: Implement getUnique() method.
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::getClass());
    }
}