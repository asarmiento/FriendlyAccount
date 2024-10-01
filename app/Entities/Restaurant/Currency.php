<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 11:04 AM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class Currency extends Entity
{

    protected  $fillable = ['name','value','school_id'];

    public function getRules()
    {
       return ['name'=>'required|unique:currencies','value'=>'required']; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
         $rules['name'] .= ',name,' . $datos['id'];// TODO: Implement getUnique() method.
        return $rules;
    }

}