<?php

namespace AccountHon\Entities;



use Illuminate\Database\Eloquent\SoftDeletes;

class AuxiliarySeat extends Entity {

    use SoftDeletes;
    protected $fillable = ['date', 'code', 'detail', 'amount',
        'financial_records_id', 'type_seat_id', 'accounting_period_id',
        'type_id','token', 'status', 'typeCollection', 'user_created', 'user_updated'];

    public function getRules()
    {
        return [
            'date'=> 'required',
            'code'=> 'required',
            'detail'=> 'required',
            'amount'=> 'required',
            'financial_records_id'=> 'required',
            'type_seat_id'=> 'required',
            'accounting_period_id'=> 'required',
            'type_id'=> 'required',
            'token'=> 'required',
            'status'=> 'required',
            'typeCollection'=> 'required',
            'type_seat_id'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function financialRecords() {
        return $this->belongsTo(FinancialRecords::getClass());
    }

    public function typeSeats() {
        return $this->belongsTo(TypeSeat::getClass(),'type_seat_id','id');
    }

    public function accountingPeriods() {
        return $this->belongsTo(AccountingPeriod::class,'accounting_period_id','id');
    }

    public function types() {
        return $this->belongsTo(TypeForm::getClass(),'type_id','id');
    }



}
