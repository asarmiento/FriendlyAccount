<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/04/16
 * Time: 11:05 PM
 */

namespace AccountHon\Entities\Accounting;


use AccountHon\Entities\Entity;

class SchoolMonthFiscal extends Entity
{

    protected $fillable = ['year','month_first','month_end','school_id'];

    public function getRules()
    {
        return ['year'=>'require','month_first'=>'require','month_end'=>'require','school_id'=>'require']; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}