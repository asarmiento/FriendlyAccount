<?php

namespace AccountHon\Entities\Accounting;



use AccountHon\Entities\Entity;

class Check extends Entity
{
    protected $fillable = ['date', 'name', 'check_number', 'amount', 'detail', 'status',
        'catalog_id', 'type_seat_id', 'accounting_period_id',
        'bank_id', 'token', 'user_created', 'user_updated'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalogs() {
        return $this->belongsTo(Catalog::getClass());
    }

    public function typeSeats() {
        return $this->belongsTo(TypeSeat::getClass());
    }

    public function accountingPeriods() {
        return $this->belongsTo(AccountingPeriod::getClass());
    }

    public function banks() {
        return $this->belongsTo(Bank::getClass());
    }

}
