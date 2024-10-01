<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/04/16
 * Time: 02:16 AM
 */

namespace AccountHon\Entities\Accounting;


use AccountHon\Entities\Entity;

class FiscalBalance extends Entity
{

    protected $fillable = ['balance','date','schools_months_fiscal_id','catalog_id'];

    public function getRules()
    {
        return ['balance'=>'required','date'=>'required','schools_months_fiscal_id'=>'required','catalog_id'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}