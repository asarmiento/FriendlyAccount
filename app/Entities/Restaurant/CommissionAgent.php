<?php

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use Illuminate\Database\Eloquent\Model;

class CommissionAgent extends Entity
{
    protected  $fillable = ['name','commission','date_of_inscription','school_id','user_id','token'];
    public function getRules()
    {
        return ['name'=>'required',
            'commission'=>'required',
            'date_of_inscription'=>'required'
            ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
       return $rules; // TODO: Implement getUnique() method.
    }
}
