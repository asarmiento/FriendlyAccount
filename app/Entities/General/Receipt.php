<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 14/02/2017
 * Time: 06:58 AM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class Receipt extends Entity
{
    protected $fillable = ['date','reference','balance','cash_desk_id','customer_id','user_id','school_id','token'];
    public function getRules()
    {
        return ['date'=>'required',
            'reference'=>'required',
            'balance'=>'required',
            'cash_desk_id'=>'required',
            'customer_id'=>'required',
            'user_id'=>'required',
            'school_id'=>'required',
            'token'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        return $rules;// TODO: Implement getUnique() method.
    }
}