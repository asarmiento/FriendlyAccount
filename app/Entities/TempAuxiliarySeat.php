<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempAuxiliarySeat extends Entity
{
    use SoftDeletes;
    protected $fillable = ['date', 'code', 'detail', 'amount',
        'financial_records_id', 'type_seat_id', 'period',
        'type_id','token', 'status', 'user_created', 'user_updated'];

    public function getRules()
    {
        return [
            'date'=> 'required',
            'code'=> 'required',
            'detail'=> 'required',
            'amount'=> 'required',
            'financial_records_id'=> 'required',
            'type_seat_id'=> 'required',
            'period'=> 'required',
            'type_id'=> 'required',
            'token'=> 'required',
            'status'=> 'required',
            'type_seat_id'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
