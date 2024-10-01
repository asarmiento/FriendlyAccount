<?php

namespace AccountHon\Entities\General;



use AccountHon\Entities\Entity;

class Employes extends Entity
{
    protected $table = 'employess';

    protected $fillable = ['fname','sname','flast','slast','charter','email','token','phone','school_id', 'user_id'];
    //
    public function getRules()
    {
        return ['fname'=>'required',
            'flast'=>'required',
            'charter'=>'required|unique:employess',
            'email'=>'required',
            'token'=>'required|unique:employess',
            'user_id'=>'required|unique:employess',
            'school_id'=>'required'];
    }

    public function getUnique($rules, $datos)
    {
        $rules['charter'] .= ',charter,' . $this->id;
        $rules['token'] .= ',token,' . $this->id;
        $rules['user_id'] .= ',user_id,' . $this->id;
        return $rules;
    }

    public function nameComplete()
    {
        return $this->fname.' '.$this->sname.' '.$this->flast.' '.$this->slast;
    }

    public function employesSchedule(){
        return $this->hasMany(EmployesSchedule::getClass());
    }
}
