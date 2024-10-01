<?php

namespace AccountHon\Entities;

class SchoolsMonthsFiscal extends Entity {

    protected $table = 'schools_months_fiscal';

    protected $fillable = ['school_id', 'month_first', 'month_end'];

    public function getRules()
    {
        return ['month_end' => 'required',
            'month_first' => 'required',
            'school_id' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        //return $rules['name'] .= ',name,' . $this->id;// TODO: Implement getUnique() method.
    }
}
