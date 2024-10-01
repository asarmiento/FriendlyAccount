<?php

namespace AccountHon\Entities;



use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Entity
{
    use SoftDeletes;
    
    public $timestamps=true;
    
    protected $fillable = ['fname','sname','flast','slast','sex','emails', 'phone', 'address', 'book', 'token','user_created', 'user_updated','school_id'];

    public function getRules()
    {
        return [
            'fname'=> 'required',
            'flast'=> 'required',
            'sex'=> 'required',
            'book'=> 'required',
            'school_id'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.

        return $rules;
    }
    public function financialRecords(){
        return $this->belongsTo(FinancialRecords::getClass(),'id','student_id')->orderBy('year','DESC');
    }

    public function school(){
        return $this->belongsTo(School::getClass());
    }
    /**
     * @return mixed
     */
    public function degreeDatos(){
        return $this->financialRecords->costs->degreeSchool->degrees;
    }

    public function nameComplete(){
        return $this->fname.' '.$this->sname.' '.$this->flast.' '.$this->slast;
    }


}
