<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 11:04 AM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class CurrencyByClosing extends Entity
{

	protected $table = 'currency_by_closing_cash_desks';
    protected $fillable = ['amount', 'closing_cash_desk_id','currency_id'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

}