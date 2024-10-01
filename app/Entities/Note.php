<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Entity
{
    
    use SoftDeletes;

    public $timestamps = true;
    // Don't forget to fill this array
    protected $fillable = ['date','description','type_id','token'];


    public function getRules()
    {
        return ['date' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $rules['date'] .= ',date,' . $this->id;// TODO: Implement getUnique() method.
        return $rules;
    }

    public function typeForms() {
        return $this->hasMany(TypeForm::getClass(),'id','type_id');
    }

}
