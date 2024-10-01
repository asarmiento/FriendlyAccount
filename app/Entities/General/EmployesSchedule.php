<?php

namespace AccountHon\Entities\General;

use AccountHon\Entities\Entity;
use Illuminate\Database\Eloquent\Model;

class EmployesSchedule extends Entity
{
    //

    protected $fillable = ['date','times','employes_id'];


    public function getRules()
    {
       return [
           'date'=>'required',
           'times'=>'required',
           'employes_id'=>'required'
       ]; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function employes()
    {
        return $this->belongsTo(Employes::getClass());
    }
}
