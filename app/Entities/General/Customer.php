<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 11:46 AM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class Customer extends  Entity
{

    protected  $fillable = ['charter',
        'fname',
        'sname',
        'email',
        'flast',
        'slast',
        'phone',
        'address',
        'date','school_id','amount','token'];

    public function getRules()
    {
        return ['charter'=>'required',
            'fname'=>'required',
            'flast'=>'required',
           // 'email'=>'required',
            'phone'=>'required',
            'school_id'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        return $rules; // TODO: Implement getUnique() method.
    }

    public function nameComplete(){
        return $this->fname.' '.$this->sname.' '.$this->flast.' '.$this->slast;
    }
}