<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingReceipt extends Entity {

    use SoftDeletes;
    protected $fillable = ['date', 'receipt_number', 'received_from', 'detail', 'amount', 'catalog_id', 'status', 'type_seat_id', 'accounting_period_id', 'token', 'user_created', 'user_updated'];


    public function getRules()
    {
        return [
            'date'                 => 'required',
            'receipt_number'       => 'required',
            'received_from'        => 'required',
            'detail'               => 'required',
            'amount'               => 'required',
            'catalog_id'           => 'required',
            'status'               => 'required',
            'type_seat_id'         => 'required',
            'accounting_period_id' => 'required',
            'token'                => 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
    
    public function catalogs() {
        return $this->belongsTo(Catalog::getClass(),'catalog_id','id');
    }

    public function typeSeats() {
        return $this->belongsTo(TypeSeat::getClass(),'type_seat_id','id');
    }

    public function accountingPeriods() {
        return $this->belongsTo(AccountingPeriod::getClass(),'accounting_period_id','id');
    }

    public function notes() {
        return $this->belongsTo(Note::getClass());
    }

    public function courtCases() {
        return $this->belongsTo(CourtCase::getClass());
    }

    public function deposits() {
        return $this->belongsTo(Deposit::getClass());
    }

    public function paymentFrom() {
        return $this->belongsTo(PaymentFrom::getClass());
    }


}
