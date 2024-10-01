<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 20:51
 */

namespace AccountHon\Entities\Accounting;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\TypeOfCompany;

class ListOfAccount extends Entity
{

    protected $fillable =['name','style','type','token','list_of_account','user_id','type_of_company_id'];

    public function getRules()
    {
        return [];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        return $rules;// TODO: Implement getUnique() method.
    }

    public function listOfAccount()
    {
        return $this->belongsTo(ListOfAccount::getClass());
    }


    public function typeOfCompany()
    {
        return $this->belongsTo(TypeOfCompany::getClass());
    }
}