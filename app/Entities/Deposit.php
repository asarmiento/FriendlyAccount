<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Entity
{
    use SoftDeletes;
    protected $fillable = ['number', 'date', 'type','catalog_id', 'amount', 'token', 'codeReceipt','school_id'];

    public function getRules()
    {
        return [
            'number'=> 'required',
            'date'=> 'required',
            'catalog_id'=> 'required',
            'amount'=> 'required',
            'token'=> 'required',
            'school_id'=> 'required',
            'codeReceipt'=> 'required',
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::getClass());
    }
}
