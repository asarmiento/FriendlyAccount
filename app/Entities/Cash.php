<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class Cash extends Entity
{
    use SoftDeletes;

    protected $fillable = ['amount', 'receipt','school_id'];

    public function getRules()
    {
        return [
            'amount'=> 'required',
            'receipt'=> 'required',
            'school_id'=>'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
