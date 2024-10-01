<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/01/2017
 * Time: 11:04 AM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class ExchangeRate extends Entity
{
    protected $fillable = ['date','buy','sale','user_id','school_id','token'];
    public function getRules()
    {
        return ['date'=>'required',
            'buy'=>'required',
            'sale'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}