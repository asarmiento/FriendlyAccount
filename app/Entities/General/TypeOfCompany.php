<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 18:31
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class TypeOfCompany extends Entity
{
    protected $fillable =['name','token','user_id'];

    public function getRules()
    {
       return []; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
       return $rules;  // TODO: Implement getUnique() method.
    }
}