<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 11/12/2016
 * Time: 08:33 PM
 */

namespace AccountHon\Entities\LawFirm;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\Customer;
use AccountHon\Entities\Restaurant\Invoice;

class SaleOfTheFirm extends Entity
{
    //protected $table = 'sales_of_the_firms';
    protected $fillable = ['description','amount','number','user_id','token','school_id'];
    public function getRules()
    {
      return [];  // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function customer()
    {
        return $this->belongsTo(Customer::getClass());
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::getClass());
    }
}