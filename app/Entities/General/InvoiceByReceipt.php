<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 14/02/2017
 * Time: 08:23 AM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class InvoiceByReceipt extends Entity
{
    protected $fillable=['invoice_id','receipt_id','amount'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}