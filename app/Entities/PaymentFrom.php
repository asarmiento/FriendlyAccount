<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentFrom extends Entity {

    public $timestamps = true;

    use SoftDeletes;

    // Don't forget to fill this array
    protected $fillable = ['name'];


    public function getRules()
    {
        return ['name' => 'required|unique:payment_froms']; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $rules['name'] .= ',name,' . $datos->id;// TODO: Implement getUnique() method.
        return $rules;
    }

    public function accountingReceipt() {
        return $this->hasMany(AccountingReceipt::getClass());
    }

    public function auxiliaryReceipt() {
        return $this->hasMany(AuxiliaryReceipt::getClass());
    }


}
