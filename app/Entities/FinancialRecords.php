<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialRecords extends Entity
{
    use SoftDeletes;
    protected $fillable = ['student_id','year','cost_id','monthly_discount','tuition_discount','retirement_date','token','balance','user_created', 'user_updated'];


    public function getRules()
    {
        return [
            'student_id'=> 'required',
            'year'=> 'required',
            'cost_id'=> 'required',
            'balance'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function students() {
        return $this->belongsTo(Student::getClass(),'student_id','id')->orderBy('fname','ASC');
    }

    public function costs(){
        return $this->belongsTo(Cost::getClass(),'cost_id','id');
    }

    public function degreeDatos(){
        return $this->costs->degreeSchool->degrees;
    }


}
