<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 11:04 AM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\CashDesk;

class ClosingCashDesk extends Entity
{
    protected $fillable = ['taxed_sales','payment_supplier', 'tax_sales', 'service_sales', 'total_sales', 'cash_desk_id', 'user_id', 'validate_user_id', 'missing', 'surplus'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function cashDesk()
    {
        return $this->belongsTo(CashDesk::getClass());
    }

}