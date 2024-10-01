<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Entity
{
    use SoftDeletes;
    protected $fillable = ['year', 'monthly_payment', 'tuition', 'shares', 'degree_school_id',  'token', 'user_created', 'user_updated'];


    public function getRules()
    {
        return ['year' => 'required',
            'monthly_payment' => 'required',
            'tuition' => 'required',
            'token' => 'required',
            'degree_school_id' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function degreeSchool() {
        return $this->belongsTo(DegreeSchool::getClass(),'degree_school_id','id');
    }

    public function financialRecords() {
        return $this->hasMany(FinancialRecords::getClass());
    }

}
