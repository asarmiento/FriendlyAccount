<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Entity {

    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['name', 'account', 'school_id', 'token', 'user_created', 'user_updated'];

    public function checks() {
        return $this->hasMany(Check::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schools() {
        return $this->belongsTo(School::getClass(),'school_id','id');
    }

    public function getRules()
    {
        return ['name' => 'required|unique:banks',
            'account' => 'required',
            'token' => 'required',
            'school_id' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        return $rules['name'] .= ',name,' . $this->id;// TODO: Implement getUnique() method.
    }
}
