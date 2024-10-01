<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 09:04 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class InventoriesIncome extends Entity
{
    protected $table = 'inventories_incomes';
    protected $fillable = ['supplier_id','invoice_id','school_id','reference','balance','token','user_id'];

    public function getRules()
    {
        return ['supplier_id'=>'required',
            'invoice_id'=>'required',
            'school_id'=>'required',
            'reference'=>'required',
            'balance'=>'required',
            'token'=>'required',
            'user_id'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::getClass());
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::getClass());
    }

}