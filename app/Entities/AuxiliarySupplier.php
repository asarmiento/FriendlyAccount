<?php

namespace AccountHon\Entities;



class AuxiliarySupplier extends Entity
{
    protected $fillable = ['date', 'code', 'detail', 'amount','type','bill',
        'supplier_id', 'type_seat_id', 'accounting_period_id','dateBuy',
        'type_id','token', 'status', 'typeCollection', 'user_created', 'user_updated'];

    public function getRules()
    {
        return [
            'date'=> 'required',
            'code'=> 'required',
            'bill'=> 'required',
            'detail'=> 'required',
            'amount'=> 'required',
            'supplier_id'=> 'required',
            'type_seat_id'=> 'required',
            'accounting_period_id'=> 'required',
            'type_id'=> 'required',
            'token'=> 'required',
            'status'=> 'required',
            'type'=> 'required',
            'type_seat_id'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
